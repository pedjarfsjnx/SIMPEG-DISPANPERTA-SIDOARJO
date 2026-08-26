<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use App\Models\Pegawai;
use App\Models\FormasiJabatan;
use Illuminate\View\View;

class StrukturOrganisasiController extends Controller
{
    public function index(): View
    {
        $unitKerjaWithBidang = UnitKerja::with(['bidang.pegawai' => function ($q) {
            $q->whereNull('deleted_at')->with(['kategori', 'formasiJabatan']);
        }, 'pegawai' => function ($q) {
            $q->whereNull('deleted_at')->with(['kategori', 'formasiJabatan']);
        }])->get();

        // Query breakdown per Eselon / Kelas Jabatan
        $eselon2b = Pegawai::with(['unitKerja', 'bidang', 'kategori', 'formasiJabatan'])
            ->whereHas('formasiJabatan', function($q) {
                $q->whereIn('kelas_jabatan', ['14', '15', '16', '17', '18']);
            })->get();

        $eselon3a = Pegawai::with(['unitKerja', 'bidang', 'kategori', 'formasiJabatan'])
            ->whereHas('formasiJabatan', function($q) {
                $q->whereIn('kelas_jabatan', ['11', '12', '13']);
            })->get();

        $eselon3b = Pegawai::with(['unitKerja', 'bidang', 'kategori', 'formasiJabatan'])
            ->whereHas('formasiJabatan', function($q) {
                $q->whereIn('kelas_jabatan', ['9', '10']);
            })->get();

        $fungsionalJft = Pegawai::with(['unitKerja', 'bidang', 'kategori', 'formasiJabatan'])
            ->whereHas('formasiJabatan', function($q) {
                $q->whereIn('kelas_jabatan', ['7', '8']);
            })->get();

        $pelaksanaJfu = Pegawai::with(['unitKerja', 'bidang', 'kategori', 'formasiJabatan'])
            ->whereHas('formasiJabatan', function($q) {
                $q->whereIn('kelas_jabatan', ['1', '2', '3', '4', '5', '6']);
            })->get();

        return view('public.struktur-organisasi', compact(
            'unitKerjaWithBidang',
            'eselon2b',
            'eselon3a',
            'eselon3b',
            'fungsionalJft',
            'pelaksanaJfu'
        ));
    }
}
