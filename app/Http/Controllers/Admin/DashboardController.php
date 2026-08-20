<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\FormasiJabatan;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();
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

        $allPegawai = Pegawai::with(['kategori', 'unitKerja', 'bidang', 'formasiJabatan'])->get();

        // 1. Rekap 5 Pensiun Terdekat dari Sekarang (>= now)
        $proyeksiPensiun = [];
        foreach ($allPegawai as $p) {
            $est = $p->estimasi_pensiun;
            if (!$est['tanggal']) continue;
            $tmt = $est['tanggal'];

            if ($tmt->gte($now)) {
                $interval = $now->diff($tmt);
                $sisaWaktu = '';
                if ($interval->y == 0 && $interval->m == 0) {
                    $sisaWaktu = 'Bulan Ini';
                } elseif ($interval->y == 0) {
                    $sisaWaktu = "{$interval->m} Bln Lagi";
                } else {
                    $sisaWaktu = "{$interval->y} Thn {$interval->m} Bln";
                }

                $proyeksiPensiun[] = (object) [
                    'pegawai_id' => $p->id,
                    'nama' => $p->nama,
                    'nip' => $p->nip,
                    'jabatan' => $p->formasiJabatan?->nama_jabatan ?? $p->jabatan ?? '-',
                    'unit_kerja' => $p->unitKerja?->nama ?? '-',
                    'tmt_pensiun' => $tmt,
                    'bup' => $est['usia'],
                    'sisa_waktu' => $sisaWaktu,
                ];
            }
        }
        usort($proyeksiPensiun, fn($a, $b) => $a->tmt_pensiun->timestamp <=> $b->tmt_pensiun->timestamp);
        $pensiunMendatang = collect(array_slice($proyeksiPensiun, 0, 5));

        // 2. Rekap 5 Kenaikan Pangkat PNS Terdekat dari Sekarang (>= now)
        $proyeksiKp = [];
        foreach ($allPegawai as $p) {
            if (!$p->is_pns) continue;
            $estKp = $p->estimasi_kp_berikutnya;
            if (!$estKp) continue;

            if ($estKp->gte($now)) {
                $interval = $now->diff($estKp);
                $sisaWaktu = '';
                if ($interval->y == 0 && $interval->m == 0) {
                    $sisaWaktu = 'Periode Ini';
                } elseif ($interval->y == 0) {
                    $sisaWaktu = "{$interval->m} Bln Lagi";
                } else {
                    $sisaWaktu = "{$interval->y} Thn {$interval->m} Bln";
                }

                $proyeksiKp[] = (object) [
                    'pegawai_id' => $p->id,
                    'nama' => $p->nama,
                    'nip' => $p->nip,
                    'golongan' => $p->golongan,
                    'jabatan' => $p->formasiJabatan?->nama_jabatan ?? $p->jabatan ?? '-',
                    'unit_kerja' => $p->unitKerja?->nama ?? '-',
                    'tmt_kp' => $estKp,
                    'sisa_waktu' => $sisaWaktu,
                ];
            }
        }
        usort($proyeksiKp, fn($a, $b) => $a->tmt_kp->timestamp <=> $b->tmt_kp->timestamp);
        $kpMendatang = collect(array_slice($proyeksiKp, 0, 5));

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