# SIMPEG DISPANPERTA SIDOARJO

Sistem Informasi Manajemen Kepegawaian (SIMPEG) Dinas Pangan dan Pertanian Kabupaten Sidoarjo.

## Prasyarat

- PHP dan Composer
- Node.js dan npm
- Git

## Opsi 1 — SQLite (paling cepat)

Pilih opsi ini bila ingin langsung menjalankan aplikasi tanpa XAMPP atau phpMyAdmin. Repository sudah menyertakan `database/database.sqlite` yang berisi data pegawai dan akun admin.

```bash
# 1. Clone repository
git clone https://github.com/pedjarfsjnx/SIMPEG-DISPANPERTA-SIDOARJO.git
cd SIMPEG-DISPANPERTA-SIDOARJO

# 2. Install dependensi
composer install
npm install

# 3. Buat file .env dan generate APP_KEY
copy .env.example .env
php artisan key:generate
```

Untuk macOS atau Linux, gunakan perintah berikut sebagai pengganti `copy`:

```bash
cp .env.example .env
```

Pastikan file `.env` menggunakan SQLite:

```env
DB_CONNECTION=sqlite
```

Jalankan aplikasi di dua terminal terpisah:

```bash
php artisan serve
```

```bash
npm run dev
```

Aplikasi akan tersedia di alamat yang ditampilkan oleh Laravel (biasanya `http://127.0.0.1:8000`) dengan data pegawai yang sudah tersedia.

## Opsi 2 — MySQL melalui XAMPP

Pilih opsi ini bila ingin menggunakan database MySQL lokal.

1. Nyalakan **Apache** dan **MySQL** pada XAMPP.
2. Buka `http://localhost/phpmyadmin`, lalu buat database bernama `dispanperta`.
3. Buat dan atur file `.env`:

```bash
copy .env.example .env
php artisan key:generate
```

4. Ubah konfigurasi database pada `.env` menjadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dispanperta
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan inisialisasi database:

```bash
php artisan simpeg:init
```

Perintah tersebut menjalankan migration dan mengimpor data pegawai SIMPEG ke MySQL. Setelah selesai, jalankan aplikasi seperti pada Opsi 1:

```bash
php artisan serve
```

```bash
npm run dev
```
