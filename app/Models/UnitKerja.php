<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';
    protected $fillable = ['nama', 'tipe', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class, 'unit_kerja_id');
    }

    public function formasiJabatan(): HasMany
    {
        return $this->hasMany(FormasiJabatan::class, 'unit_kerja_id');
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'unit_kerja_id');
    }
}
