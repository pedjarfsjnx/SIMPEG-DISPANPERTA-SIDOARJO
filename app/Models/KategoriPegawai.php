<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPegawai extends Model
{
    protected $table = 'kategori_pegawai';
    protected $fillable = ['nama'];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'kategori_pegawai_id');
    }
}
