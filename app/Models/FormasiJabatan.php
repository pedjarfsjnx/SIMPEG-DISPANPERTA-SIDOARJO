<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormasiJabatan extends Model
{
    protected $table = 'formasi_jabatan';
    protected $fillable = [
        'unit_kerja_id',
        'bidang_id',
        'nama_jabatan',
        'kelas_jabatan',
        'status_formasi',
        'aktif'
    ];
    protected $casts = ['aktif' => 'boolean'];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'formasi_jabatan_id');
    }
}
