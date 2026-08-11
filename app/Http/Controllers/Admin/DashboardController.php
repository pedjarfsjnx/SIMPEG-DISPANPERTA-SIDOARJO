<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\FormasiJabatan;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\RiwayatPensiun;
use App\Models\RiwayatKenaikanPangkat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPegawai = Pegawai::count();
        $totalPns = Pegawai::whereHas('kategori', fn($q) => $q->where('nama', 'PNS'))->count();
        $totalPppk = Pegawai::whereHas('kategori', fn($q) => $q->where('nama', 'LIKE', 'PPPK%'))->count();
        
        $formasiTerisiCount = FormasiJabatan::where('status_formasi', 'terisi')->count();
        $formasiKosongCount = FormasiJabatan::where('status_formasi', 'kosong')->orWhereNull('status_formasi')->count();

        $rekapKategori = KategoriPegawai::withCount('pegawai')->get();
        $rekapStatus = StatusKepegawaian::withCount('pegawai')->get();
        $rekapUnitKerja = UnitKerja::withCount('pegawai')->get();

        $recentPegawai = Pegawai::with(['kategori', 'unitKerja', 'bidang'])
            ->latest()
            ->take(5)
            ->get();

        $pensiunMendatang = RiwayatPensiun::with('pegawai')
            ->orderBy('tmt_pensiun', 'asc')
            ->take(5)
            ->get();

        $kpMendatang = RiwayatKenaikanPangkat::with('pegawai')
            ->orderBy('tmt_diusulkan', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPegawai',
            'totalPns',
            'totalPppk',
            'formasiTerisiCount',
            'formasiKosongCount',
            'rekapKategori',
            'rekapStatus',
            'rekapUnitKerja',
            'recentPegawai',
            'pensiunMendatang',
            'kpMendatang'
        ));
    }
}
