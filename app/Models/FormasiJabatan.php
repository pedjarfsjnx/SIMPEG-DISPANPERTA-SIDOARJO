<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormasiJabatan extends Model
{
    use HasFactory;

    protected $table = 'formasi_jabatan';

    protected $fillable = [
        'unit_kerja_id',
        'bidang_id',
        'nama_jabatan',
        'kelas_jabatan',
        'kuota',
        'status_formasi',
    ];

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

    public function getEselonLabelAttribute(): string
    {
        if (!$this->kelas_jabatan || !is_numeric($this->kelas_jabatan)) {
            return '-';
        }
        $kelas = (int) $this->kelas_jabatan;
        if ($kelas >= 14) return 'Kelas 14 (Eselon II.b)';
        if ($kelas >= 11) return "Kelas {$kelas} (Eselon III.a)";
        if ($kelas >= 9) return "Kelas {$kelas} (Eselon III.b)";
        if ($kelas >= 7) return "Kelas {$kelas} (Fungsional JFT)";
        return "Kelas {$kelas} (Pelaksana JFU)";
    }
}
