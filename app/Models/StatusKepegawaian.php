<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusKepegawaian extends Model
{
    protected $table = 'status_kepegawaian';
    protected $fillable = ['nama'];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'status_kepegawaian_id');
    }
}
