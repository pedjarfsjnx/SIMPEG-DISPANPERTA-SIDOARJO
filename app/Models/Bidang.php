<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = ['unit_kerja_id', 'nama', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function formasiJabatan(): HasMany
    {
        return $this->hasMany(FormasiJabatan::class, 'bidang_id');
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'bidang_id');
    }
}
