<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPensiun extends Model
{
    protected $table = 'riwayat_pensiun';
    protected $fillable = [
        'pegawai_id',
        'tanggal_pengajuan',
        'tmt_pensiun',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tmt_pensiun' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
