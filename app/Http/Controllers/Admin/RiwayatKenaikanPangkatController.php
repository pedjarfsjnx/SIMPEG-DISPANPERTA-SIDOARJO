<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatKenaikanPangkat;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class RiwayatKenaikanPangkatController extends Controller
{
    public function index(Request $request): View
    {
        $query = RiwayatKenaikanPangkat::with(['pegawai.unitKerja', 'pegawai.bidang']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $kpList = $query->orderBy('tmt_diusulkan', 'asc')->paginate(15)->withQueryString();
        $totalUsulan = RiwayatKenaikanPangkat::count();

        return view('admin.kenaikan-pangkat.index', compact('kpList', 'totalUsulan'));
    }

    public function create(): View
    {
        // Hanya tampilkan pegawai yang belum pensiun
        $now = Carbon::now();
        $pegawaiList = Pegawai::with(['riwayatPensiun', 'formasiJabatan'])
            ->orderBy('nama', 'asc')
            ->get()
            ->filter(function($p) use ($now) {
                // Jika sudah ada catatan riwayat pensiun yang lewat
                $pensiunResmi = $p->riwayatPensiun->sortByDesc('tmt_pensiun')->first();
                if ($pensiunResmi && $pensiunResmi->tmt_pensiun && $pensiunResmi->tmt_pensiun->lt($now)) {
                    return false;
                }
                // Jika BUP sudah lewat
                $est = $p->estimasi_pensiun;
                if ($est['tanggal'] && $est['tanggal']->lt($now)) {
                    return false;
                }
                // Hanya PNS yang berhak naik pangkat
                if (!$p->is_pns) {
                    return false;
                }
                return true;
            });

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

        $pegawai = Pegawai::with('riwayatPensiun')->findOrFail($request->pegawai_id);
        $tmtUsulan = Carbon::parse($request->tmt_diusulkan);

        // REVISI RULE: Pegawai yang pensiun tidak boleh diajukan kenaikan pangkat setelah tanggal pensiunnya!
        $pensiunResmi = $pegawai->riwayatPensiun->sortByDesc('tmt_pensiun')->first();
        if ($pensiunResmi && $pensiunResmi->tmt_pensiun) {
            if ($tmtUsulan->gt($pensiunResmi->tmt_pensiun)) {
                return back()->withInput()->withErrors([
                    'tmt_diusulkan' => "Tidak dapat mengajukan kenaikan pangkat per {$tmtUsulan->format('d/m/Y')}. Pegawai {$pegawai->nama} telah tercatat purna tugas (pensiun) pada {$pensiunResmi->tmt_pensiun->format('d/m/Y')}."
                ]);
            }
        }

        // Cek juga dari Batas Usia Pensiun (BUP 58/60 thn)
        $estimasi = $pegawai->estimasi_pensiun;
        if ($estimasi['tanggal'] && $tmtUsulan->gt($estimasi['tanggal'])) {
            return back()->withInput()->withErrors([
                'tmt_diusulkan' => "Tidak dapat mengajukan kenaikan pangkat per {$tmtUsulan->format('d/m/Y')}. Pegawai {$pegawai->nama} mencapai Batas Usia Pensiun ({$estimasi['usia']} tahun) pada {$estimasi['tanggal']->format('d/m/Y')}."
            ]);
        }

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
