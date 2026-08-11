<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $kategoriList = KategoriPegawai::withCount('pegawai')->get();
        $statusList = StatusKepegawaian::withCount('pegawai')->get();
        $unitKerjaList = UnitKerja::withCount(['bidang', 'pegawai'])->get();
        $bidangList = Bidang::with('unitKerja')->withCount('pegawai')->get();

        return view('admin.master-data.index', compact(
            'kategoriList',
            'statusList',
            'unitKerjaList',
            'bidangList'
        ));
    }

    public function storeUnitKerja(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'tipe' => 'required|string|max:50',
        ]);

        UnitKerja::create($validated);

        return redirect()->route('admin.master-data.index')->with('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function storeBidang(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'nama' => 'required|string|max:150',
        ]);

        Bidang::create($validated);

        return redirect()->route('admin.master-data.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function storeKategori(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_pegawai,nama',
        ]);

        KategoriPegawai::create($validated);

        return redirect()->route('admin.master-data.index')->with('success', 'Kategori Pegawai berhasil ditambahkan.');
    }

    public function storeStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:status_kepegawaian,nama',
        ]);

        StatusKepegawaian::create($validated);

        return redirect()->route('admin.master-data.index')->with('success', 'Status Kepegawaian berhasil ditambahkan.');
    }
}
