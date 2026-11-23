@echo off
if "%1"=="--open-browser" goto BUKA_BROWSER_READY

title SIMPEG DISPANPERTA SIDOARJO - SERVER RUNNER
color 0A

:: 1. Masuk ke folder proyek
cd /d "%~dp0"
if not exist "artisan" cd /d "C:\xampp\htdocs\dispanperta"

echo ======================================================================
echo    MEMULAI SERVER SISTEM INFORMASI KEPEGAWAIAN (SIMPEG)
echo    DINAS PANGAN DAN PERTANIAN KABUPATEN SIDOARJO
echo ======================================================================
echo.

:: 2. Pastikan PHP XAMPP ada di PATH
where php >nul 2>&1
if not errorlevel 1 goto CEK_MYSQL_PATH

if exist "C:\xampp\php\php.exe" goto TAMBAH_PHP_PATH

color 0C
echo [ERROR] PHP tidak ditemukan di sistem atau di C:\xampp\php!
echo Pastikan XAMPP sudah terpasang.
echo.
pause
exit /b

:TAMBAH_PHP_PATH
set PATH=C:\xampp\php;%PATH%

:CEK_MYSQL_PATH
where mysqladmin >nul 2>&1
if not errorlevel 1 goto MULAI_SERVER
if exist "C:\xampp\mysql\bin" set PATH=C:\xampp\mysql\bin;%PATH%

:MULAI_SERVER
:: 3. Cek dan Nyalakan Database MySQL Otomatis
echo [1/4] Memeriksa status Database MySQL...
where mysqladmin >nul 2>&1
if errorlevel 1 goto LEWAT_MYSQL_CHECK

mysqladmin -u root ping >nul 2>&1
if not errorlevel 1 goto MYSQL_OK

echo [INFO] Database MySQL belum aktif. Menyalakan MySQL otomatis...
net start mysql >nul 2>&1
if errorlevel 1 (
    start "" /b "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone >nul 2>&1
)
:: Jeda 3 detik agar database siap
ping -n 4 127.0.0.1 >nul
echo [OK] Database MySQL siap digunakan.
goto NGROK_CHECK

:MYSQL_OK
echo [OK] Database MySQL sudah aktif.
goto NGROK_CHECK

:LEWAT_MYSQL_CHECK
echo [INFO] Memeriksa database...
if exist "C:\xampp\mysql\bin\mysqld.exe" (
    start "" /b "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone >nul 2>&1
    ping -n 4 127.0.0.1 >nul
)

:NGROK_CHECK
:: 4. Aktifkan Tunnel Online Permanen (Ngrok) untuk Akses HP / Bit.ly
echo [2/4] Menghubungkan jalur akses online (Ngrok)...
taskkill /f /im ngrok.exe >nul 2>&1
if exist "%~dp0ngrok.exe" (
    "%~dp0ngrok.exe" config add-authtoken 3JLMxPJ70AhgBAgiKp2sq9OlIXj_24HDreB3MMjKHm4UZjUX1 >nul 2>&1
    start "" /b "%~dp0ngrok.exe" http 8000 --url=postbox-revolving-exponent.ngrok-free.dev >nul 2>&1
    echo [OK] Jalur Online Siap: https://postbox-revolving-exponent.ngrok-free.dev
)

:IP_CHECK
:: 5. Deteksi informasi dinamis laptop server & IP jaringan lokal kantor
echo [3/4] Mendeteksi informasi jaringan laptop server...
set LOCAL_IP=
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /i "IPv4" ^| findstr /v "169.254"') do (
    if not defined LOCAL_IP (
        for /f "tokens=1" %%j in ("%%i") do set LOCAL_IP=%%j
    )
)
if not defined LOCAL_IP set LOCAL_IP=127.0.0.1

:: 6. Buka browser otomatis saat server sudah benar-benar siap (hanya 1 tab)
echo [4/4] Menyiapkan web server dan browser otomatis...
start "" /b cmd /c ""%~f0" --open-browser"

:: 7. Tampilan panduan lengkap mengikuti identitas laptop server
echo.
echo ======================================================================
echo  STATUS: SERVER SIMPEG BERHASIL DIAKTIFKAN!
echo ======================================================================
echo  [INFORMASI LAPTOP SERVER INI]:
echo   * Nama Perangkat Server : %COMPUTERNAME%
echo   * User Akun Windows     : %USERNAME%
echo   * Alamat IP Jaringan    : %LOCAL_IP%
echo.
echo ----------------------------------------------------------------------
echo  PANDUAN ALAMAT AKSES APLIKASI:
echo ----------------------------------------------------------------------
echo  [1] LINK UTAMA RESMI (Akses HP, Android/iOS, dan Luar Kantor):
echo      https://bit.ly/simpeg-dispanperta-sidoarjo
echo      (Tautan Langsung: https://postbox-revolving-exponent.ngrok-free.dev)
echo.
echo  [2] LINK KHUSUS DI LAPTOP SERVER INI:
echo      http://localhost:8000
echo.
echo  [3] LINK JARINGAN WI-FI KANTOR (Laptop / HP di Wi-Fi yang sama):
echo      http://%LOCAL_IP%:8000
echo      (atau: http://%COMPUTERNAME%:8000)
echo ----------------------------------------------------------------------
echo.
echo  PETUNJUK PENGGUNAAN:
echo  - Berikan link https://bit.ly/simpeg-dispanperta-sidoarjo ke pegawai kantor.
echo  - Browser di laptop ini akan terbuka otomatis dalam beberapa detik.
echo  - JANGAN TUTUP jendela ini selama aplikasi SIMPEG digunakan kantor.
echo  - Jika jam kantor selesai, cukup klik tombol X (tutup) di pojok kanan atas.
echo.
echo ======================================================================
echo  LOG AKTIVITAS SERVER:
echo ======================================================================
echo.

:: 8. Jalankan server Laravel
php artisan serve --host=0.0.0.0 --port=8000

taskkill /f /im ngrok.exe >nul 2>&1
pause
exit /b

:BUKA_BROWSER_READY
:: Memantau hingga server Laravel aktif, lalu membuka tepat 1 tab browser
for /l %%i in (1,1,30) do (
    curl -s http://127.0.0.1:8000 >nul 2>&1
    if not errorlevel 1 (
        start http://localhost:8000
        exit /b
    )
    ping -n 2 127.0.0.1 >nul
)
:: Fallback jika 30 detik belum ada respons
start http://localhost:8000
exit /b
