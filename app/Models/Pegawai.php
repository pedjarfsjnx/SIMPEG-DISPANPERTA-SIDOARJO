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

    // Helper: Apakah Pegawai adalah PNS Murni (Bukan PPPK / Honorer)
    public function getIsPnsAttribute(): bool
    {
        $kategoriNama = strtoupper($this->kategori?->nama ?? '');
        return ($this->kategori_pegawai_id === 1 || str_contains($kategoriNama, 'PNS')) 
            && !str_contains($kategoriNama, 'PPPK') 
            && !str_contains($kategoriNama, 'NON-PNS')
            && !empty($this->golongan) 
            && $this->golongan !== '-';
    }

    // Helper: Apakah Pegawai adalah PPPK
    public function getIsPppkAttribute(): bool
    {
        $kategoriNama = strtoupper($this->kategori?->nama ?? '');
        return str_contains($kategoriNama, 'PPPK');
    }

    // Accessor: Usia Pegawai Saat Ini
    public function getUsiaAttribute(): ?int
    {
        $tgl = $this->tanggal_lahir_effektif;
        return $tgl ? $tgl->age : null;
    }

    // Accessor: Tanggal Lahir (Eksplisit atau dari 8 Digit NIP)
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

    // Accessor: Batas Usia Pensiun (60 Thn HANYA untuk Pimpinan Tinggi/Kadis & Fungsional Madya/Utama; 58 Thn untuk lainnya)
    public function getBatasUsiaPensiunAttribute(): int
    {
        $jabatan = strtoupper($this->formasiJabatan?->nama_jabatan ?? $this->jabatan ?? '');
        $kelas = (int) ($this->formasiJabatan?->kelas_jabatan ?? 0);

        // 1. Kepala Dinas / Pejabat Pimpinan Tinggi (Eselon II, Kelas >= 14) -> 60 Tahun
        if ($kelas >= 14 || str_contains($jabatan, 'KEPALA DINAS') || str_contains($jabatan, 'PIMPINAN TINGGI')) {
            return 60;
        }

        // 2. Fungsional Jenjang Ahli Madya / Ahli Utama (Golongan IV) -> 60 / 65 Tahun
        if (str_contains($jabatan, 'AHLI MADYA') || str_contains($jabatan, 'AHLI UTAMA') || str_contains($jabatan, 'MADYA') || str_contains($jabatan, 'UTAMA')) {
            return 60;
        }

        // 3. Fungsional Kategori Terampil (Pemula/Terampil/Mahir/Penyelia), Fungsional Ahli Pertama & Muda, Pelaksana, Administrasi -> 58 Tahun
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

    // Accessor: Estimasi Jadwal Kenaikan Pangkat Reguler (Khusus PNS, disesuaikan 6 Periode BKN)
    public function getEstimasiKpBerikutnyaAttribute(): ?Carbon
    {
        // Kenaikan Pangkat HANYA untuk PNS yang memiliki golongan
        if (!$this->is_pns || !$this->tmt_jabatan) {
            return null;
        }
        
        $tmt = $this->tmt_jabatan->copy();
        $now = Carbon::now();
        
        while ($tmt->isPast()) {
            $tmt->addYears(4);
        }

        // 6 Periode Kenaikan Pangkat Resmi BKN (1 Feb, 1 Apr, 1 Jun, 1 Ags, 1 Okt, 1 Des)
        $month = (int) $tmt->format('m');
        $year = (int) $tmt->format('Y');

        $periodeBkn = [2, 4, 6, 8, 10, 12];
        $targetMonth = 2;
        $targetYear = $year;

        foreach ($periodeBkn as $pMonth) {
            if ($pMonth >= $month) {
                $targetMonth = $pMonth;
                break;
            }
        }
        if ($month > 12) {
            $targetMonth = 2;
            $targetYear = $year + 1;
        }

        $tmtKp = Carbon::createFromDate($targetYear, $targetMonth, 1);

        // Jika tanggal KP melebihi batas usia pensiun, maka tidak ada kenaikan pangkat setelah pensiun
        $estPensiun = $this->estimasi_pensiun['tanggal'];
        if ($estPensiun && $tmtKp->gt($estPensiun)) {
            return null;
        }
        
        return $tmtKp;
    }
}