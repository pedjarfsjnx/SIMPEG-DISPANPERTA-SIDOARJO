<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatKenaikanPangkat extends Model
{
    protected $table = 'riwayat_kenaikan_pangkat';
    protected $fillable = [
        'pegawai_id',
        'golongan_lama',
        'golongan_baru',
        'tmt_diusulkan',
        'keterangan',
    ];

    protected $casts = [
        'tmt_diusulkan' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
