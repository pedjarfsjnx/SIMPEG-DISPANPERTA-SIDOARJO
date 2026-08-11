<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatKenaikanPangkat;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RiwayatKenaikanPangkatController extends Controller
{
    public function index(): View
    {
        $kpList = RiwayatKenaikanPangkat::with(['pegawai.unitKerja', 'pegawai.bidang'])
            ->orderBy('tmt_diusulkan', 'asc')
            ->get();

        return view('admin.kenaikan-pangkat.index', compact('kpList'));
    }

    public function create(): View
    {
        $pegawaiList = Pegawai::orderBy('nama', 'asc')->get();
        return view('admin.kenaikan-pangkat.create', compact('pegawaiList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'golongan_lama' => 'nullable|string|max:30',
            'golongan_baru' => 'required|string|max:30',
            'tmt_diusulkan' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        RiwayatKenaikanPangkat::create($validated);

        return redirect()->route('admin.kenaikan-pangkat.index')->with('success', 'Data pengajuan kenaikan pangkat berhasil ditambahkan.');
    }

    public function destroy($id): RedirectResponse
    {
        $kp = RiwayatKenaikanPangkat::findOrFail($id);
        $kp->delete();

        return redirect()->route('admin.kenaikan-pangkat.index')->with('success', 'Data pengajuan kenaikan pangkat berhasil dihapus.');
    }
}
