<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pegawai::withTrashed()->with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->input('trashed') === 'only') {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_pegawai_id', $request->input('kategori_id'));
        }

        $pegawaiList = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();
        $kategoriOptions = KategoriPegawai::all();

        return view('admin.pegawai.index', compact('pegawaiList', 'kategoriOptions'));
    }

    public function create(): View
    {
        $kategoriOptions = KategoriPegawai::all();
        $statusOptions = StatusKepegawaian::all();
        $unitKerjaOptions = UnitKerja::all();
        $bidangOptions = Bidang::all();
        $formasiOptions = FormasiJabatan::where('status_formasi', 'kosong')->orWhereNull('status_formasi')->get();

        return view('admin.pegawai.create', compact(
            'kategoriOptions',
            'statusOptions',
            'unitKerjaOptions',
            'bidangOptions',
            'formasiOptions'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_pegawai_id' => 'required|exists:kategori_pegawai,id',
            'status_kepegawaian_id' => 'required|exists:status_kepegawaian,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'formasi_jabatan_id' => 'nullable|exists:formasi_jabatan,id',
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:30|unique:pegawai,nip',
            'nik' => 'nullable|string|max:20|unique:pegawai,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'tmt_jabatan' => 'nullable|date',
        ]);

        $pegawai = Pegawai::create($validated);

        if ($pegawai->formasi_jabatan_id) {
            FormasiJabatan::where('id', $pegawai->formasi_jabatan_id)->update(['status_formasi' => 'terisi']);
        }

        ActivityLog::log('CREATE', "Menambahkan pegawai baru: {$pegawai->nama}");

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show($id): View
    {
        $pegawai = Pegawai::withTrashed()->with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan', 'riwayatPensiun', 'riwayatKenaikanPangkat'])
            ->findOrFail($id);

        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function edit($id): View
    {
        $pegawai = Pegawai::findOrFail($id);
        $kategoriOptions = KategoriPegawai::all();
        $statusOptions = StatusKepegawaian::all();
        $unitKerjaOptions = UnitKerja::all();
        $bidangOptions = Bidang::all();
        $formasiOptions = FormasiJabatan::all();

        return view('admin.pegawai.edit', compact(
            'pegawai',
            'kategoriOptions',
            'statusOptions',
            'unitKerjaOptions',
            'bidangOptions',
            'formasiOptions'
        ));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'kategori_pegawai_id' => 'required|exists:kategori_pegawai,id',
            'status_kepegawaian_id' => 'required|exists:status_kepegawaian,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'formasi_jabatan_id' => 'nullable|exists:formasi_jabatan,id',
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:30|unique:pegawai,nip,' . $id,
            'nik' => 'nullable|string|max:20|unique:pegawai,nik,' . $id,
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'tmt_jabatan' => 'nullable|date',
        ]);

        $pegawai->update($validated);

        ActivityLog::log('UPDATE', "Memperbarui data pegawai: {$pegawai->nama}");

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $pegawai = Pegawai::findOrFail($id);
        $nama = $pegawai->nama;
        $pegawai->delete();

        ActivityLog::log('DELETE', "Mengarsipkan pegawai: {$nama}");

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diarsipkan (Soft Delete).');
    }

    public function restore($id): RedirectResponse
    {
        $pegawai = Pegawai::onlyTrashed()->findOrFail($id);
        $pegawai->restore();

        ActivityLog::log('RESTORE', "Memulihkan data pegawai: {$pegawai->nama}");

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dipulihkan.');
    }
}
