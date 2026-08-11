<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pegawai_id', $request->input('kategori_id'));
        }

        if ($request->filled('status_id')) {
            $query->where('status_kepegawaian_id', $request->input('status_id'));
        }

        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->input('unit_kerja_id'));
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->input('bidang_id'));
        }

        if ($request->filled('kelas_jabatan')) {
            $kelasReq = $request->input('kelas_jabatan');
            $query->whereHas('formasiJabatan', function($q) use ($kelasReq) {
                if ($kelasReq === 'eselon_2') {
                    $q->where('kelas_jabatan', '>=', 14);
                } elseif ($kelasReq === 'eselon_3a') {
                    $q->whereBetween('kelas_jabatan', [11, 13]);
                } elseif ($kelasReq === 'eselon_3b') {
                    $q->whereBetween('kelas_jabatan', [9, 10]);
                } elseif ($kelasReq === 'fungsional') {
                    $q->whereBetween('kelas_jabatan', [7, 8]);
                } elseif ($kelasReq === 'pelaksana') {
                    $q->where('kelas_jabatan', '<', 7);
                }
            });
        }

        $pegawaiList = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();

        $kategoriOptions = KategoriPegawai::all();
        $statusOptions = StatusKepegawaian::all();
        $unitKerjaOptions = UnitKerja::all();
        $bidangOptions = Bidang::all();

        return view('public.pegawai.index', compact(
            'pegawaiList',
            'kategoriOptions',
            'statusOptions',
            'unitKerjaOptions',
            'bidangOptions'
        ));
    }

    public function show($id): View
    {
        $pegawai = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan', 'riwayatPensiun', 'riwayatKenaikanPangkat'])
            ->findOrFail($id);

        return view('public.pegawai.show', compact('pegawai'));
    }

    public function cetak(Request $request): View
    {
        $query = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pegawai_id', $request->input('kategori_id'));
        }
        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->input('unit_kerja_id'));
        }

        $pegawaiList = $query->orderBy('nama', 'asc')->get();

        return view('public.pegawai.cetak', compact('pegawaiList'));
    }

    public function downloadPdf(Request $request)
    {
        $query = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pegawai_id', $request->input('kategori_id'));
        }
        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->input('unit_kerja_id'));
        }

        $pegawaiList = $query->orderBy('nama', 'asc')->get();

        $pdf = Pdf::loadView('public.pegawai.cetak', compact('pegawaiList'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Data_Pegawai_Dispanperta_Sidoarjo.pdf');
    }
}
