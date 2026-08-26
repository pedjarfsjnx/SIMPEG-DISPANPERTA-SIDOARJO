<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPegawai;
use App\Models\Pegawai;
use App\Models\RiwayatPensiun;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class RiwayatPensiunController extends Controller
{
    public function index(Request $request): View
    {
        $currentYear = (int) date('Y');
        $now = Carbon::now();

        // 1. Ambil seluruh pegawai aktif
        $pegawaiQuery = Pegawai::with(['unitKerja', 'bidang', 'formasiJabatan', 'kategori', 'riwayatPensiun']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $pegawaiQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('unit_kerja_id')) {
            $pegawaiQuery->where('unit_kerja_id', (int) $request->input('unit_kerja_id'));
        }

        if ($request->filled('kategori_id')) {
            $pegawaiQuery->where('kategori_pegawai_id', (int) $request->input('kategori_id'));
        }

        $allPegawai = $pegawaiQuery->get();

        // 2. Hitung proyeksi pensiun otomatis dari NIP + BUP
        $rekapList = [];
        foreach ($allPegawai as $p) {
            $est = $p->estimasi_pensiun;
            if (! $est['tanggal']) {
                continue;
            }

            $tmtPensiun = $est['tanggal'];
            $bulanPensiun = (int) $tmtPensiun->format('m');
            $tahunPensiun = (int) $tmtPensiun->format('Y');

            // Cek jika ada catatan khusus di tabel riwayat_pensiun
            $riwayatResmi = $p->riwayatPensiun->sortByDesc('tmt_pensiun')->first();

            // Filter Bulan jika ada
            if ($request->filled('bulan') && (int) $request->input('bulan') !== $bulanPensiun) {
                continue;
            }

            // Filter Tahun jika ada
            if ($request->filled('tahun') && (int) $request->input('tahun') !== $tahunPensiun) {
                continue;
            }

            // Hitung sisa masa kerja dengan integer DateInterval
            $interval = $now->diff($tmtPensiun);
            $diffYears = (int) $interval->y;
            $diffMonths = (int) $interval->m;
            $isPast = (bool) $interval->invert;

            $statusWaktu = '';
            if ($isPast || $tmtPensiun->lt($now)) {
                $statusWaktu = 'Purna Tugas';
            } elseif ($diffYears == 0 && $diffMonths <= 6) {
                $statusWaktu = '< 6 Bulan (Mendesak)';
            } elseif ($diffYears == 0) {
                $statusWaktu = "{$diffMonths} Bulan Lagi";
            } else {
                $statusWaktu = "{$diffYears} Thn {$diffMonths} Bln";
            }

            $rekapList[] = (object) [
                'pegawai_id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nip,
                'kategori' => $p->kategori?->nama ?? 'PNS',
                'unit_kerja' => $p->unitKerja?->nama ?? '-',
                'bidang' => $p->bidang?->nama ?? '-',
                'jabatan' => $p->formasiJabatan?->nama_jabatan ?? $p->jabatan ?? '-',
                'tanggal_lahir' => $p->tanggal_lahir_effektif,
                'bup' => $est['usia'],
                'tmt_pensiun' => $tmtPensiun,
                'bulan' => $bulanPensiun,
                'tahun' => $tahunPensiun,
                'sisa_waktu' => $statusWaktu,
                'keterangan_khusus' => $riwayatResmi?->keterangan ?? 'BUP Reguler',
                'has_riwayat' => ! empty($riwayatResmi),
                'riwayat_id' => $riwayatResmi?->id,
            ];
        }

        // 3. Urutkan berdasarkan TMT Pensiun terdekat
        usort($rekapList, fn ($a, $b) => $a->tmt_pensiun->timestamp <=> $b->tmt_pensiun->timestamp);

        // 4. Hitung Statistik Ringkasan
        $allProyeksi = Pegawai::all()->map(fn ($p) => $p->estimasi_pensiun['tanggal'])->filter();
        $totalPNS = $allProyeksi->count();
        $pensiunTahunIni = $allProyeksi->filter(fn ($d) => $d->format('Y') == $currentYear)->count();
        $pensiunTahunDepan = $allProyeksi->filter(fn ($d) => $d->format('Y') == ($currentYear + 1))->count();
        $pensiun5Tahun = $allProyeksi->filter(fn ($d) => $d->format('Y') >= $currentYear && $d->format('Y') <= ($currentYear + 5))->count();

        // 5. Pagination manual untuk collection
        $perPage = 20;
        $page = $request->input('page', 1);
        $totalItems = count($rekapList);
        $offset = ($page - 1) * $perPage;
        $itemsForCurrentPage = array_slice($rekapList, $offset, $perPage);
        $pensiunList = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $totalItems,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $unitKerjaList = UnitKerja::orderBy('nama')->get();
        $kategoriList = KategoriPegawai::orderBy('nama')->get();

        // Opsi Tahun dari 2025 s.d. 2035
        $tahunOptions = range($currentYear, $currentYear + 10);

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

        return view('admin.pensiun.index', compact(
            'pensiunList',
            'unitKerjaList',
            'kategoriList',
            'bulanOptions',
            'tahunOptions',
            'totalPNS',
            'pensiunTahunIni',
            'pensiunTahunDepan',
            'pensiun5Tahun'
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

        return redirect()->route('admin.pensiun.index')->with('success', 'Catatan pengajuan pensiun berhasil disimpan.');
    }

    public function destroy($id): RedirectResponse
    {
        $pensiun = RiwayatPensiun::findOrFail($id);
        $pensiun->delete();

        return redirect()->route('admin.pensiun.index')->with('success', 'Data pengajuan pensiun berhasil dihapus.');
    }
}
