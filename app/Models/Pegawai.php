<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
