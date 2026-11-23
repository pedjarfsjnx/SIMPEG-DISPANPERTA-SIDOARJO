@echo off
title UPDATE APLIKASI SIMPEG DARI GITHUB
color 0B
cd /d "%~dp0"

echo ======================================================================
echo    MEMPERBARUI SISTEM INFORMASI KEPEGAWAIAN (SIMPEG)
echo    MENGAMBIL KODINGAN TERBARU DARI GITHUB...
echo ======================================================================
echo.

:: 1. Pastikan PHP ada di PATH (gunakan goto agar terhindar dari bug kurung PATH Windows)
where php >nul 2>&1
if not errorlevel 1 goto CEK_GIT
if exist "C:\xampp\php\php.exe" set "PATH=C:\xampp\php;%PATH%"

:CEK_GIT
:: 2. Pastikan Git terpasang
where git >nul 2>&1
if not errorlevel 1 goto MULAI_PULL

color 0C
echo [ERROR] Git belum terpasang di laptop ini!
echo Silakan unduh dan pasang Git dari https://git-scm.com/
echo.
pause
exit /b

:MULAI_PULL
:: 3. Ambil kodingan terbaru dan terapkan langsung dari GitHub (paling ampuh & anti-nyangkut)
echo Menghubungkan ke GitHub dan mengunduh pembaruan...
echo.
git fetch origin main
if errorlevel 1 goto GAGAL_PULL

git reset --hard origin/main
if errorlevel 1 goto GAGAL_PULL

:: 4. Sinkronisasi struktur database dan bersihkan cache Laravel otomatis
echo.
echo Memeriksa dan memperbarui struktur database...
where php >nul 2>&1
if not errorlevel 1 (
    php artisan migrate --force
    php artisan optimize:clear
)

echo.
echo ======================================================================
echo  [BERHASIL] Kodingan SIMPEG dan file dispanperta.sql sudah paling update!
echo  Lokasi file SQL: %~dp0dispanperta.sql
echo ======================================================================
echo.
pause
exit /b

:GAGAL_PULL
color 0C
echo.
echo ======================================================================
echo  [PERINGATAN] Gagal mengunduh pembaruan dari GitHub!
echo  Periksa koneksi internet Anda atau pastikan izin akses repository aman.
echo ======================================================================
echo.
pause
exit /b
