<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPensiun;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RiwayatPensiunController extends Controller
{
    public function index(): View
    {
        $pensiunList = RiwayatPensiun::with(['pegawai.unitKerja', 'pegawai.bidang'])
            ->orderBy('tmt_pensiun', 'asc')
            ->get();

        return view('admin.pensiun.index', compact('pensiunList'));
    }

    public function create(): View
    {
        $pegawaiList = Pegawai::orderBy('nama', 'asc')->get();
        return view('admin.pensiun.create', compact('pegawaiList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'tanggal_pengajuan' => 'nullable|date',
            'tmt_pensiun' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        RiwayatPensiun::create($validated);

        return redirect()->route('admin.pensiun.index')->with('success', 'Data pengajuan pensiun berhasil ditambahkan.');
    }

    public function destroy($id): RedirectResponse
    {
        $pensiun = RiwayatPensiun::findOrFail($id);
        $pensiun->delete();

        return redirect()->route('admin.pensiun.index')->with('success', 'Data pengajuan pensiun berhasil dihapus.');
    }
}
