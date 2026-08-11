<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pegawai::with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan']);

        if ($request->filled('trashed') && $request->input('trashed') === 'only') {
            $query->onlyTrashed();
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

        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->input('unit_kerja_id'));
        }

        $pegawaiList = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();

        $kategoriOptions = KategoriPegawai::all();
        $unitKerjaOptions = UnitKerja::all();

        return view('admin.pegawai.index', compact('pegawaiList', 'kategoriOptions', 'unitKerjaOptions'));
    }

    public function create(): View
    {
        $kategoriList = KategoriPegawai::all();
        $statusList = StatusKepegawaian::all();
        $unitKerjaList = UnitKerja::all();
        $bidangList = Bidang::all();
        $formasiList = FormasiJabatan::where('status_formasi', 'kosong')->get();

        return view('admin.pegawai.create', compact(
            'kategoriList',
            'statusList',
            'unitKerjaList',
            'bidangList',
            'formasiList'
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
            'nama' => 'required|string|max:200',
            'nip' => 'nullable|string|max:30',
            'nik' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:30',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'tmt_jabatan' => 'nullable|date',
        ]);

        $pegawai = Pegawai::create($validated);

        // Jika formasi jabatan dipilih, update status formasi menjadi terisi
        if (!empty($validated['formasi_jabatan_id'])) {
            FormasiJabatan::where('id', $validated['formasi_jabatan_id'])->update(['status_formasi' => 'terisi']);
        }

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show($id): View
    {
        $pegawai = Pegawai::withTrashed()
            ->with(['kategori', 'status', 'unitKerja', 'bidang', 'formasiJabatan', 'riwayatPensiun', 'riwayatKenaikanPangkat'])
            ->findOrFail($id);

        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function edit($id): View
    {
        $pegawai = Pegawai::findOrFail($id);

        $kategoriList = KategoriPegawai::all();
        $statusList = StatusKepegawaian::all();
        $unitKerjaList = UnitKerja::all();
        $bidangList = Bidang::where('unit_kerja_id', $pegawai->unit_kerja_id)->get();
        $formasiList = FormasiJabatan::where('status_formasi', 'kosong')
            ->orWhere('id', $pegawai->formasi_jabatan_id)
            ->get();

        return view('admin.pegawai.edit', compact(
            'pegawai',
            'kategoriList',
            'statusList',
            'unitKerjaList',
            'bidangList',
            'formasiList'
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
            'nama' => 'required|string|max:200',
            'nip' => 'nullable|string|max:30',
            'nik' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:30',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'tmt_jabatan' => 'nullable|date',
        ]);

        $oldFormasiId = $pegawai->formasi_jabatan_id;
        $newFormasiId = $validated['formasi_jabatan_id'] ?? null;

        $pegawai->update($validated);

        // Update status formasi lama dan baru jika berubah
        if ($oldFormasiId && $oldFormasiId != $newFormasiId) {
            FormasiJabatan::where('id', $oldFormasiId)->update(['status_formasi' => 'kosong']);
        }
        if ($newFormasiId) {
            FormasiJabatan::where('id', $newFormasiId)->update(['status_formasi' => 'terisi']);
        }

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $pegawai = Pegawai::findOrFail($id);
        
        if ($pegawai->formasi_jabatan_id) {
            FormasiJabatan::where('id', $pegawai->formasi_jabatan_id)->update(['status_formasi' => 'kosong']);
        }

        $pegawai->delete(); // Soft Delete

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diarsipkan (soft delete).');
    }

    public function restore($id): RedirectResponse
    {
        $pegawai = Pegawai::onlyTrashed()->findOrFail($id);
        $pegawai->restore();

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dipulihkan dari arsip.');
    }
}
