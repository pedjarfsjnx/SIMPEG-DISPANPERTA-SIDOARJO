<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
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
            if (!$row || count(array_filter($row)) === 0) continue;

            // Simple header detection (e.g. searching for NAMA or NIP)
            $firstCell = strtoupper((string)($row[0] ?? ''));
            $secondCell = strtoupper((string)($row[1] ?? ''));

            if (!$headerFound && (str_contains($firstCell, 'NAMA') || str_contains($secondCell, 'NAMA') || str_contains($firstCell, 'NO'))) {
                $headerFound = true;
                continue;
            }

            if ($headerFound) {
                // Remove leading apostrophe from NIP
                $nip = trim((string)($row[1] ?? ''));
                if (str_starts_with($nip, "'")) {
                    $nip = substr($nip, 1);
                }

                $previewRows[] = [
                    'nama' => trim((string)($row[0] ?? '')),
                    'nip' => $nip,
                    'jabatan' => trim((string)($row[2] ?? '')),
                    'golongan' => trim((string)($row[3] ?? '')),
                    'pendidikan' => trim((string)($row[4] ?? '')),
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
            if (empty($row['nama'])) continue;

            Pegawai::create([
                'kategori_pegawai_id' => $pnsKat->id,
                'status_kepegawaian_id' => $aktifStatus->id,
                'unit_kerja_id' => $dinasUnit->id,
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

    public function exportExcel(): StreamedResponse
    {
        $pegawaiList = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pegawai');

        // Header Row
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIP');
        $sheet->setCellValue('D1', 'NIK');
        $sheet->setCellValue('E1', 'KATEGORI');
        $sheet->setCellValue('F1', 'STATUS');
        $sheet->setCellValue('G1', 'UNIT KERJA');
        $sheet->setCellValue('H1', 'BIDANG');
        $sheet->setCellValue('I1', 'JABATAN');
        $sheet->setCellValue('J1', 'GOLONGAN');
        $sheet->setCellValue('K1', 'PENDIDIKAN');

        $rowNum = 2;
        foreach ($pegawaiList as $index => $p) {
            $sheet->setCellValue('A' . $rowNum, $index + 1);
            $sheet->setCellValue('B' . $rowNum, $p->nama);
            $sheet->setCellValueExplicit('C' . $rowNum, $p->nip ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $rowNum, $p->nik ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $p->kategori?->nama ?? '');
            $sheet->setCellValue('F' . $rowNum, $p->status?->nama ?? '');
            $sheet->setCellValue('G' . $rowNum, $p->unitKerja?->nama ?? '');
            $sheet->setCellValue('H' . $rowNum, $p->bidang?->nama ?? '');
            $sheet->setCellValue('I' . $rowNum, $p->formasiJabatan?->nama_jabatan ?? '');
            $sheet->setCellValue('J' . $rowNum, $p->golongan ?? '');
            $sheet->setCellValue('K' . $rowNum, $p->pendidikan ?? '');
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'DATA_PEGAWAI_DISPANPERTA_SIDOARJO_' . date('Ymd_His') . '.xlsx';

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
