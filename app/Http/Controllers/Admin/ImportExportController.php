<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
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
