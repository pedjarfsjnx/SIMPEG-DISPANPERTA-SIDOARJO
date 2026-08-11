<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\View\View;

class StrukturOrganisasiController extends Controller
{
    public function index(): View
    {
        $unitKerjaWithBidang = UnitKerja::with(['bidang.pegawai.kategori', 'formasiJabatan.pegawai'])
            ->where('aktif', true)
            ->get();

        return view('public.struktur-organisasi', compact('unitKerjaWithBidang'));
    }
}
