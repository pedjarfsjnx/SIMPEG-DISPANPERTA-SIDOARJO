<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\RiwayatPensiun;
use App\Models\RiwayatKenaikanPangkat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPegawai = Pegawai::count();

        // Rekap per Kategori
        $rekapKategori = KategoriPegawai::withCount('pegawai')->get();

        // Rekap per Status
        $rekapStatus = StatusKepegawaian::withCount('pegawai')->get();

        // Rekap per Unit Kerja
        $rekapUnitKerja = UnitKerja::withCount('pegawai')->get();

        // Rekap Pensiun & KP Periode Mendatang
        $pensiunMendatang = RiwayatPensiun::with('pegawai.kategori', 'pegawai.unitKerja', 'pegawai.bidang')
            ->whereNotNull('tmt_pensiun')
            ->orderBy('tmt_pensiun', 'asc')
            ->take(5)
            ->get();

        $kpMendatang = RiwayatKenaikanPangkat::with('pegawai.kategori', 'pegawai.unitKerja', 'pegawai.bidang')
            ->whereNotNull('tmt_diusulkan')
            ->orderBy('tmt_diusulkan', 'asc')
            ->take(5)
            ->get();

        return view('public.dashboard', compact(
            'totalPegawai',
            'rekapKategori',
            'rekapStatus',
            'rekapUnitKerja',
            'pensiunMendatang',
            'kpMendatang'
        ));
    }
}
