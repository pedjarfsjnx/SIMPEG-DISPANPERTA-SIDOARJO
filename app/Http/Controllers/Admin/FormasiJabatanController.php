<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormasiJabatan;
use App\Models\UnitKerja;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FormasiJabatanController extends Controller
{
    public function index(): View
    {
        $formasiList = FormasiJabatan::with(['unitKerja', 'bidang', 'pegawai'])->get();
        return view('admin.formasi-jabatan.index', compact('formasiList'));
    }

    public function create(): View
    {
        $unitKerjaList = UnitKerja::where('aktif', true)->get();
        $bidangList = Bidang::where('aktif', true)->get();
        return view('admin.formasi-jabatan.create', compact('unitKerjaList', 'bidangList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'nama_jabatan' => 'required|string|max:200',
            'kelas_jabatan' => 'nullable|string|max:20',
            'status_formasi' => 'required|in:kosong,terisi',
        ]);

        FormasiJabatan::create($validated);

        return redirect()->route('admin.formasi-jabatan.index')->with('success', 'Formasi Jabatan berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $formasi = FormasiJabatan::findOrFail($id);
        $unitKerjaList = UnitKerja::where('aktif', true)->get();
        $bidangList = Bidang::where('unit_kerja_id', $formasi->unit_kerja_id)->get();

        return view('admin.formasi-jabatan.edit', compact('formasi', 'unitKerjaList', 'bidangList'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $formasi = FormasiJabatan::findOrFail($id);

        $validated = $request->validate([
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'nama_jabatan' => 'required|string|max:200',
            'kelas_jabatan' => 'nullable|string|max:20',
            'status_formasi' => 'required|in:kosong,terisi',
        ]);

        $formasi->update($validated);

        return redirect()->route('admin.formasi-jabatan.index')->with('success', 'Formasi Jabatan berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $formasi = FormasiJabatan::findOrFail($id);
        $formasi->delete();

        return redirect()->route('admin.formasi-jabatan.index')->with('success', 'Formasi Jabatan berhasil dihapus.');
    }
}
