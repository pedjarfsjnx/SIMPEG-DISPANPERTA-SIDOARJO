<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = 'pegawai';
    protected $fillable = [
        'kategori_pegawai_id',
        'status_kepegawaian_id',
        'unit_kerja_id',
        'bidang_id',
        'formasi_jabatan_id',
        'nama',
        'nip',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'golongan',
        'no_hp',
        'email',
        'tmt_jabatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_jabatan' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPegawai::class, 'kategori_pegawai_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function formasiJabatan(): BelongsTo
    {
        return $this->belongsTo(FormasiJabatan::class, 'formasi_jabatan_id');
    }

    public function riwayatPensiun(): HasMany
    {
        return $this->hasMany(RiwayatPensiun::class, 'pegawai_id');
    }

    public function riwayatKenaikanPangkat(): HasMany
    {
        return $this->hasMany(RiwayatKenaikanPangkat::class, 'pegawai_id');
    }

    // Accessor: Usia Pegawai Saat Ini
    public function getUsiaAttribute(): ?int
    {
        $tgl = $this->tanggal_lahir;
        if (!$tgl && $this->nip) {
            $clean = preg_replace('/[^0-9]/', '', $this->nip);
            if (strlen($clean) >= 8) {
                try {
                    $tgl = Carbon::createFromFormat('Ymd', substr($clean, 0, 8));
                } catch (\Exception $e) {}
            }
        }
        return $tgl ? $tgl->age : null;
    }

    // Accessor: Tanggal Lahir (Eksplisit atau dari NIP)
    public function getTanggalLahirEffektifAttribute(): ?Carbon
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir;
        }
        if ($this->nip) {
            $clean = preg_replace('/[^0-9]/', '', $this->nip);
            if (strlen($clean) >= 8) {
                try {
                    return Carbon::createFromFormat('Ymd', substr($clean, 0, 8));
                } catch (\Exception $e) {}
            }
        }
        return null;
    }

        // Accessor: Batas Usia Pensiun (60 Thn untuk Kepala & Fungsional, 58 Thn untuk Pelaksana)
    public function getBatasUsiaPensiunAttribute(): int
    {
        $jabatan = strtoupper($this->formasiJabatan?->nama_jabatan ?? $this->jabatan ?? '');
        $kelas = (int) ($this->formasiJabatan?->kelas_jabatan ?? 0);

        // 1. Kepala Dinas / Pimpinan Tinggi / Eselon II / Jabatan Struktural Kepala (60 Tahun)
        if ($kelas >= 14 || str_contains($jabatan, 'KEPALA DINAS') || str_contains($jabatan, 'KEPALA')) {
            return 60;
        }

        // 2. Jabatan Fungsional (JFT) -> Ahli, Fungsional, Medik Veteriner, Penyuluh, Dokter Hewan, dll. (60 Tahun)
        if (
            str_contains($jabatan, 'AHLI') ||
            str_contains($jabatan, 'FUNGSIONAL') ||
            str_contains($jabatan, 'VETERINER') ||
            str_contains($jabatan, 'PENYULUH') ||
            str_contains($jabatan, 'PENGAWAS') ||
            str_contains($jabatan, 'DOKTER') ||
            str_contains($jabatan, 'PRANATA') ||
            str_contains($jabatan, 'ANALIS') ||
            str_contains($jabatan, 'AUDITOR')
        ) {
            return 60;
        }

        // 3. Pelaksana / Administrasi Umum (58 Tahun)
        return 58;
    }

    // Accessor: Estimasi Tanggal Batas Usia Pensiun
    public function getEstimasiPensiunAttribute(): array
    {
        $tglLahir = $this->tanggal_lahir_effektif;
        $batasUsia = $this->batas_usia_pensiun;
        if (!$tglLahir) {
            return ['usia' => $batasUsia, 'tanggal' => null];
        }

        $tglPensiun = $tglLahir->copy()->addYears($batasUsia)->endOfMonth();

        return [
            'usia' => $batasUsia,
            'tanggal' => $tglPensiun
        ];
    }

    // Accessor: Estimasi Jadwal Kenaikan Pangkat Reguler (Setiap 4 Tahun dari TMT)
    public function getEstimasiKpBerikutnyaAttribute(): ?Carbon
    {
        if (!$this->tmt_jabatan) {
            return null;
        }
        
        $tmt = $this->tmt_jabatan->copy();
        $now = Carbon::now();
        
        while ($tmt->isPast()) {
            $tmt->addYears(4);
        }
        
        return $tmt;
    }
}
