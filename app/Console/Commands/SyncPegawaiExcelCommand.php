<?php

namespace App\Console\Commands;

use App\Models\Bidang;
use App\Models\KategoriPegawai;
use App\Models\Pegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SyncPegawaiExcelCommand extends Command
{
    protected $signature = 'simpeg:sync-pegawai-excel {file : Path ke file Excel sumber}';

    protected $description = 'Menyelaraskan data pegawai dari Excel berdasarkan NIP atau nama tanpa menghapus data yang ada';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("File Excel tidak ditemukan: {$file}");

            return self::FAILURE;
        }

        $rows = IOFactory::load($file)->getActiveSheet()->toArray(null, true, true, false);
        $header = array_map(fn ($value) => $this->normalizeText($value), array_shift($rows) ?? []);
        $expectedHeader = ['NO', 'NAMA', 'NIP', 'NIK', 'UNIT KERJA', 'BIDANG', 'JABATAN', 'GOLONGAN', 'PENDIDIKAN'];

        if (array_slice($header, 0, count($expectedHeader)) !== $expectedHeader) {
            $this->error('Format kolom Excel tidak sesuai. Kolom yang dibutuhkan: '.implode(', ', $expectedHeader));

            return self::FAILURE;
        }

        $sourceRows = array_values(array_filter($rows, fn (array $row) => trim((string) ($row[1] ?? '')) !== ''));
        $sourceKeys = [];

        foreach ($sourceRows as $row) {
            $key = $this->sourceKey($row);

            if (isset($sourceKeys[$key])) {
                $this->error("Data duplikat pada Excel untuk {$key}.");

                return self::FAILURE;
            }

            $sourceKeys[$key] = true;
        }

        $summary = [
            'created' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'bidang_created' => 0,
        ];

        DB::transaction(function () use ($sourceRows, &$summary) {
            $units = UnitKerja::all()->keyBy(fn (UnitKerja $unit) => $this->normalizeText($unit->nama));
            $pegawaiByNip = Pegawai::withTrashed()->whereNotNull('nip')->get()->keyBy(fn (Pegawai $pegawai) => trim((string) $pegawai->nip));
            $pegawaiByName = Pegawai::withTrashed()->get()->keyBy(fn (Pegawai $pegawai) => $this->normalizeName($pegawai->nama));
            $defaultKategori = KategoriPegawai::where('nama', 'PNS')->first() ?? KategoriPegawai::firstOrFail();
            $defaultStatus = StatusKepegawaian::where('nama', 'Aktif')->first() ?? StatusKepegawaian::firstOrFail();

            foreach ($sourceRows as $row) {
                $nama = trim((string) ($row[1] ?? ''));
                $nip = trim((string) ($row[2] ?? ''));
                $nik = trim((string) ($row[3] ?? ''));
                $unitName = trim((string) ($row[4] ?? ''));
                $bidangName = trim((string) ($row[5] ?? ''));
                $golongan = trim((string) ($row[7] ?? ''));
                $pendidikan = trim((string) ($row[8] ?? ''));
                $unit = $units->get($this->normalizeText($unitName));

                if (! $unit) {
                    throw new \RuntimeException("Unit kerja '{$unitName}' pada Excel belum terdaftar.");
                }

                $bidang = null;

                if ($bidangName !== '') {
                    $bidang = Bidang::firstOrCreate(
                        ['unit_kerja_id' => $unit->id, 'nama' => $bidangName],
                        ['aktif' => true]
                    );

                    if ($bidang->wasRecentlyCreated) {
                        $summary['bidang_created']++;
                    }
                }

                $attributes = [
                    'nama' => $nama,
                    'nip' => $nip !== '' ? $nip : null,
                    'nik' => $nik !== '' ? $nik : null,
                    'unit_kerja_id' => $unit->id,
                    'bidang_id' => $bidang?->id,
                    'golongan' => $golongan !== '' ? $golongan : null,
                    'pendidikan' => $pendidikan !== '' ? $pendidikan : null,
                ];

                $pegawai = $nip !== ''
                    ? $pegawaiByNip->get($nip)
                    : $pegawaiByName->get($this->normalizeName($nama));

                if (! $pegawai) {
                    Pegawai::create($attributes + [
                        'kategori_pegawai_id' => $defaultKategori->id,
                        'status_kepegawaian_id' => $defaultStatus->id,
                    ]);
                    $summary['created']++;

                    continue;
                }

                $pegawai->fill($attributes);

                if ($pegawai->isDirty()) {
                    $pegawai->save();
                    $summary['updated']++;
                } else {
                    $summary['unchanged']++;
                }
            }
        });

        $this->info("Sinkronisasi selesai: {$summary['created']} pegawai baru, {$summary['updated']} diperbarui, {$summary['unchanged']} tidak berubah.");
        $this->info("Master bidang baru dibuat: {$summary['bidang_created']}. Tidak ada data pegawai yang dihapus.");

        return self::SUCCESS;
    }

    private function sourceKey(array $row): string
    {
        $nip = trim((string) ($row[2] ?? ''));

        return $nip !== '' ? "nip:{$nip}" : 'nama:'.$this->normalizeName($row[1] ?? '');
    }

    private function normalizeText(mixed $value): string
    {
        return mb_strtoupper(trim((string) $value));
    }

    private function normalizeName(mixed $value): string
    {
        return preg_replace('/[^\pL\pN]/u', '', $this->normalizeText($value));
    }
}
