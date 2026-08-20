<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPensiun;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class RiwayatPensiunController extends Controller
{
    public function index(Request $request): View
    {
        $query = RiwayatPensiun::with(['pegawai.unitKerja', 'pegawai.bidang', 'pegawai.formasiJabatan']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bulan')) {
            $bulan = (int) $request->input('bulan');
            $query->whereMonth('tmt_pensiun', $bulan);
        }

        if ($request->filled('tahun')) {
            $tahun = (int) $request->input('tahun');
            $query->whereYear('tmt_pensiun', $tahun);
        }

        if ($request->filled('unit_kerja_id')) {
            $unitId = (int) $request->input('unit_kerja_id');
            $query->whereHas('pegawai', function($q) use ($unitId) {
                $q->where('unit_kerja_id', $unitId);
            });
        }

        $pensiunList = $query->orderBy('tmt_pensiun', 'asc')->paginate(20)->withQueryString();
        $unitKerjaList = UnitKerja::orderBy('nama')->get();

        // Opsi Tahun dari data pensiun & 5 tahun ke depan
        $currentYear = (int) date('Y');
        $tahunOptions = range($currentYear - 2, $currentYear + 5);

        $bulanOptions = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Rekap Stats
        $totalPensiun = RiwayatPensiun::count();
        $pensiunTahunIni = RiwayatPensiun::whereYear('tmt_pensiun', $currentYear)->count();
        $pensiunMendatang = RiwayatPensiun::where('tmt_pensiun', '>=', date('Y-m-d'))->count();

        return view('admin.pensiun.index', compact(
            'pensiunList',
            'unitKerjaList',
            'bulanOptions',
            'tahunOptions',
            'totalPensiun',
            'pensiunTahunIni',
            'pensiunMendatang'
        ));
    }

    public function create(): View
    {
        $pegawaiList = Pegawai::with('formasiJabatan')->orderBy('nama', 'asc')->get();
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