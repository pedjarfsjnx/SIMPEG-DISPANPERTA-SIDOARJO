<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\DashboardController as PublicDashboardController;
use App\Http\Controllers\Public\PegawaiController as PublicPegawaiController;
use App\Http\Controllers\Public\StrukturOrganisasiController as PublicStrukturController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PegawaiController as AdminPegawaiController;
use App\Http\Controllers\Admin\FormasiJabatanController as AdminFormasiController;
use App\Http\Controllers\Admin\MasterDataController as AdminMasterDataController;
use App\Http\Controllers\Admin\RiwayatPensiunController as AdminPensiunController;
use App\Http\Controllers\Admin\RiwayatKenaikanPangkatController as AdminKPController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Public Routes (Website Internal - Read Only)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicDashboardController::class, 'index'])->name('public.dashboard');
Route::get('/pegawai', [PublicPegawaiController::class, 'index'])->name('public.pegawai.index');
Route::get('/pegawai-cetak', [PublicPegawaiController::class, 'cetak'])->name('public.pegawai.cetak');
Route::get('/pegawai-download-pdf', [PublicPegawaiController::class, 'downloadPdf'])->name('public.pegawai.download-pdf');
Route::get('/pegawai/{id}', [PublicPegawaiController::class, 'show'])->name('public.pegawai.show');
Route::get('/struktur-organisasi', [PublicStrukturController::class, 'index'])->name('public.struktur-organisasi');

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Pegawai Management
    Route::post('/pegawai/{id}/restore', [AdminPegawaiController::class, 'restore'])->name('pegawai.restore');
    Route::resource('pegawai', AdminPegawaiController::class);

    // Formasi Jabatan Management
    Route::resource('formasi-jabatan', AdminFormasiController::class);

    // Master Data Management
    Route::get('/master-data', [AdminMasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/master-data/unit-kerja', [AdminMasterDataController::class, 'storeUnitKerja'])->name('master-data.unit-kerja.store');
    Route::post('/master-data/bidang', [AdminMasterDataController::class, 'storeBidang'])->name('master-data.bidang.store');
    Route::post('/master-data/kategori', [AdminMasterDataController::class, 'storeKategori'])->name('master-data.kategori.store');
    Route::post('/master-data/status', [AdminMasterDataController::class, 'storeStatus'])->name('master-data.status.store');

    // Riwayat Pensiun & KP Management
    Route::resource('pensiun', AdminPensiunController::class);
    Route::resource('kenaikan-pangkat', AdminKPController::class);

    // Activity Audit Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Import / Export Excel & PDF
    Route::get('/import', [ImportExportController::class, 'showImportForm'])->name('import.form');
    Route::post('/import/preview', [ImportExportController::class, 'previewImport'])->name('import.preview');
    Route::post('/import/commit', [ImportExportController::class, 'commitImport'])->name('import.commit');
    Route::get('/export', [ImportExportController::class, 'exportExcel'])->name('export.excel');
});

require __DIR__.'/auth.php';
