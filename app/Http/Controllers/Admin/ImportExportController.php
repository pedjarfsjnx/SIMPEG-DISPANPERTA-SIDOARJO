<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use App\Models\KategoriPegawai;
use App\Models\Pegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
    public function showImportForm(): View
    {
        return view('admin.import-export.import');
    }

    public function previewImport(Request $request): View|RedirectResponse
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $previewRows = [];
        $headerFound = false;

        // Clean & parse Excel rows (skipping Kop headers)
        foreach ($rows as $rowIndex => $row) {
            if (! $row || count(array_filter($row)) === 0) {
                continue;
            }

            // Simple header detection (e.g. searching for NAMA or NIP)
            $firstCell = strtoupper((string) ($row[0] ?? ''));
            $secondCell = strtoupper((string) ($row[1] ?? ''));

            if (! $headerFound && (str_contains($firstCell, 'NAMA') || str_contains($secondCell, 'NAMA') || str_contains($firstCell, 'NO'))) {
                $headerFound = true;

                continue;
            }

            if ($headerFound) {
                // Remove leading apostrophe from NIP
                $nip = trim((string) ($row[1] ?? ''));
                if (str_starts_with($nip, "'")) {
                    $nip = substr($nip, 1);
                }

                $previewRows[] = [
                    'nama' => trim((string) ($row[0] ?? '')),
                    'nip' => $nip,
                    'jabatan' => trim((string) ($row[2] ?? '')),
                    'golongan' => trim((string) ($row[3] ?? '')),
                    'pendidikan' => trim((string) ($row[4] ?? '')),
                ];
            }
        }

        // Save preview in session for confirmation
        session(['import_preview_data' => $previewRows]);

        return view('admin.import-export.preview', compact('previewRows'));
    }

    public function commitImport(Request $request): RedirectResponse
    {
        $previewRows = session('import_preview_data', []);

        if (empty($previewRows)) {
            return redirect()->route('admin.import.form')->with('error', 'Tidak ada data impor yang dapat disimpan.');
        }

        $pnsKat = KategoriPegawai::where('nama', 'PNS')->first() ?? KategoriPegawai::first();
        $aktifStatus = StatusKepegawaian::where('nama', 'Aktif')->first() ?? StatusKepegawaian::first();
        $dinasUnit = UnitKerja::first();

        $count = 0;
        foreach ($previewRows as $row) {
            if (empty($row['nama'])) {
                continue;
            }

            $formasiId = null;
            if (! empty($row['jabatan'])) {
                $formasi = FormasiJabatan::firstOrCreate([
                    'nama_jabatan' => $row['jabatan'],
                    'unit_kerja_id' => $dinasUnit->id,
                ]);
                $formasiId = $formasi->id;
            }

            Pegawai::create([
                'kategori_pegawai_id' => $pnsKat->id,
                'status_kepegawaian_id' => $aktifStatus->id,
                'unit_kerja_id' => $dinasUnit->id,
                'formasi_jabatan_id' => $formasiId,
                'nama' => $row['nama'],
                'nip' => $row['nip'] ?: null,
                'golongan' => $row['golongan'] ?: null,
                'pendidikan' => $row['pendidikan'] ?: null,
            ]);
            $count++;
        }

        session()->forget('import_preview_data');

        return redirect()->route('admin.pegawai.index')->with('success', "Berhasil mengimpor {$count} data pegawai dari Excel.");
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Pegawai::withTrashed()->with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->input('trashed') === 'only') {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%")
                    ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pegawai_id', $request->input('kategori_id'));
        }

        if ($request->filled('status_id')) {
            $query->where('status_kepegawaian_id', $request->input('status_id'));
        }

        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->integer('unit_kerja_id'));
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->integer('bidang_id'));
        }

        $sortBy = $request->input('sort_by', 'nama_asc');
        match ($sortBy) {
            'nama_desc' => $query->orderBy('nama', 'desc'),
            'unit_kerja_asc', 'unit_kerja_desc' => $query->orderBy(
                UnitKerja::select('nama')->whereColumn('unit_kerja.id', 'pegawai.unit_kerja_id'),
                $sortBy === 'unit_kerja_asc' ? 'asc' : 'desc'
            )->orderBy('nama'),
            'bidang_asc', 'bidang_desc' => $query->orderBy(
                Bidang::select('nama')->whereColumn('bidang.id', 'pegawai.bidang_id'),
                $sortBy === 'bidang_asc' ? 'asc' : 'desc'
            )->orderBy('nama'),
            default => $query->orderBy('nama', 'asc'),
        };

        $pegawaiList = $query->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pegawai');

        // Header Row Styling
        $headers = [
            'A1' => 'NO',
            'B1' => 'NAMA LENGKAP',
            'C1' => 'NIP',
            'D1' => 'NIK',
            'E1' => 'KATEGORI',
            'F1' => 'STATUS',
            'G1' => 'UNIT KERJA',
            'H1' => 'BIDANG / SUB-UNIT',
            'I1' => 'JABATAN',
            'J1' => 'GOLONGAN',
            'K1' => 'PENDIDIKAN',
            'L1' => 'NO TELEPON',
            'M1' => 'EMAIL',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $rowNum = 2;
        foreach ($pegawaiList as $index => $p) {
            $sheet->setCellValue('A'.$rowNum, $index + 1);
            $sheet->setCellValue('B'.$rowNum, $p->nama);
            $sheet->setCellValueExplicit('C'.$rowNum, $p->nip ?? '', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D'.$rowNum, $p->nik ?? '', DataType::TYPE_STRING);
            $sheet->setCellValue('E'.$rowNum, $p->kategori?->nama ?? '');
            $sheet->setCellValue('F'.$rowNum, $p->status?->nama ?? '');
            $sheet->setCellValue('G'.$rowNum, $p->unitKerja?->nama ?? '');
            $sheet->setCellValue('H'.$rowNum, $p->bidang?->nama ?? '');
            $sheet->setCellValue('I'.$rowNum, $p->formasiJabatan?->nama_jabatan ?? $p->jabatan ?? '');
            $sheet->setCellValue('J'.$rowNum, $p->golongan ?? '');
            $sheet->setCellValue('K'.$rowNum, $p->pendidikan ?? '');
            $sheet->setCellValueExplicit('L'.$rowNum, $p->no_hp ?? '', DataType::TYPE_STRING);
            $sheet->setCellValue('M'.$rowNum, $p->email ?? '');
            $rowNum++;
        }

        // Auto size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $totalCount = $pegawaiList->count();
        $filename = 'DATA_PEGAWAI_DISPANPERTA_('.$totalCount.'_Data)_'.date('Ymd_His').'.xlsx';

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
