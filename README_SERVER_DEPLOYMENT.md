# PANDUAN DEPLOYMENT SIMPEG DINAS PANGAN DAN PERTANIAN KABUPATEN SIDOARJO
## Panduan Teknis Deployment ke Server VPS / cPanel Resmi Pemkab (.go.id)

---

### 📦 1. KEBUTUHAN SISTEM (SYSTEM REQUIREMENTS)
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **PHP**: Version 8.2 atau 8.3 (Extension: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD)
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Node.js**: Version 18+ (Untuk build Vite assets)

---

### 🚀 2. LANGKAH DEPLOYMENT DI CPANEL RESMI PEMKAB (.GO.ID)

1. **Membuat Subdomain & Database MySQL**:
   - Buka cPanel server Pemkab Sidoarjo (`https://dispanperta.sidoarjo.go.id:2083`).
   - Masuk ke menu **Subdomains** -> Buat subdomain baru: `simpeg.dispanperta.sidoarjo.go.id`.
   - Masuk ke menu **MySQL Databases** -> Buat database baru bernama `dispanperta_simpeg`, buat User MySQL, dan berikan `ALL PRIVILEGES`.

2. **Import Database SQL**:
   - Masuk ke **phpMyAdmin** cPanel.
   - Pilih database `dispanperta_simpeg`.
   - Klik tab **Import**, upload file `simpeg_database_dump.sql` (Terdiri dari 149 data pegawai murni 2026).

3. **Upload Files & Konfigurasi .env**:
   - Upload seluruh source code project ke folder root subdomain (misal: `/public_html/simpeg`).
   - Edit file `.env` di File Manager:
     ```env
     APP_NAME="SIMPEG DISPANPERTA"
     APP_ENV=production
     APP_KEY=base64:xxx...
     APP_DEBUG=false
     APP_URL=https://simpeg.dispanperta.sidoarjo.go.id

     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=dispanperta_simpeg
     DB_USERNAME=user_db_cpanel
     DB_PASSWORD=password_db_cpanel
     ```

4. **Arahkan Root Document (Public)**:
   - Di cPanel Subdomain Manager, ubah Document Root subdomain menjadi:
     `/public_html/simpeg/public`
   - Berikan hak akses chmod `775` atau `777` pada folder:
     - `storage/`
     - `bootstrap/cache/`

---

### 🖥️ 3. LANGKAH DEPLOYMENT DI VPS LINUX (UBUNTU / NGINX)

1. **Clone & Install Dependencies**:
   ```bash
   cd /var/www
   git clone <repo-url> simpeg
   cd simpeg
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```

2. **Migrasi & Import Data**:
   ```bash
   php artisan migrate --force
   php artisan import:real-data
   ```

3. **Set Permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/simpeg
   sudo chmod -R 775 /var/www/simpeg/storage /var/www/simpeg/bootstrap/cache
   ```

4. **Nginx Virtual Host Configuration**:
   ```nginx
   server {
       listen 80;
       server_name simpeg.dispanperta.sidoarjo.go.id;
       root /var/www/simpeg/public;

       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
       }
   }
   ```

---

### 🔒 4. INFORMASI KREDENSIAL DEFAULT ADMIN
- **URL Admin**: `https://simpeg.dispanperta.sidoarjo.go.id/login`
- **Email**: `admin@dispanperta.sidoarjo.go.id`
- **Password**: `password` (Segera ubah password setelah login pertama).
