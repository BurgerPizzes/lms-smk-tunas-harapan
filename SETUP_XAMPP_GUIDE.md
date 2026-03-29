# Panduan Menjalankan LMS SMK Tunas Harapan
## VS Code + XAMPP (Windows)

---

## PERSIAPAN

### 1. Download & Install Software
Pastikan software berikut sudah terinstall:

| Software | Download | Versi Minimum |
|----------|---------|---------------|
| XAMPP | https://www.apachefriends.org/ | 8.2+ (PHP 8.2) |
| VS Code | https://code.visualstudio.com/ | Latest |
| Composer | https://getcomposer.org/Composer-Setup.exe | 2.6+ |
| Git | https://git-scm.com/download/win | Latest |
| Node.js | https://nodejs.org/ | 18 LTS+ |

> **PENTING**: XAMPP harus yang PHP 8.2 atau lebih baru (Laravel 11 wajib PHP 8.2+)

### 2. Cek Versi PHP di XAMPP
Buka XAMPP Control Panel → Start Apache → buka browser:
```
http://localhost/dashboard/phpinfo.php
```
Pastikan PHP Version = 8.2.x atau lebih tinggi.

---

## LANGKAH DEMI LANGKAH

### STEP 1: Clone / Salin Project

**Opsi A — Clone dari GitHub:**
```
git clone https://github.com/BurgerPizzes/lms-smk-tunas-harapan.git
```

**Opsi B — Download ZIP:**
1. Buka https://github.com/BurgerPizzes/lms-smk-tunas-harapan
2. Klik tombol hijau "Code" → "Download ZIP"
3. Extract ke: `C:\xampp\htdocs\lms-smk-tunas-harapan`

### STEP 2: Buka di VS Code
```
1. Buka VS Code
2. File → Open Folder
3. Pilih folder: C:\xampp\htdocs\lms-smk-tunas-harapan
```

### STEP 3: Buka Terminal di VS Code
```
1. Tekan Ctrl + `
   (backtick, tombol di sebelah angka 1)
2. Atau: Terminal → New Terminal
```

### STEP 4: Install Composer Dependencies
```bash
composer install
```

Tunggu sampai selesai (bisa 2-5 menit tergantung internet).

> Jika error "Composer not found", tutup VS Code, buka XAMPP Shell,
> lalu jalankan: `composer install`

### STEP 5: Copy File Environment
```bash
copy .env.example .env
```

### STEP 6: Setup Database MySQL

**6a. Buka phpMyAdmin:**
```
http://localhost/phpmyadmin
```

**6b. Buat Database Baru:**
- Klik tab "New" di sidebar kiri
- Database name: `lms_smk_tunas_harapan`
- Collation: `utf8mb4_unicode_ci`
- Klik "Create"

**6c. (Opsional) Buat User Khusus:**
- Klik tab "Privileges" → "Add user account"
- Username: `lms_user`
- Password: `lms_password`
- Host: `localhost`
- Global privileges: Check "SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP"
- Klik "Go"

### STEP 7: Edit File .env
Buka file `.env` di VS Code, ubah bagian database:

```env
DB_DATABASE=lms_smk_tunas_harapan
DB_USERNAME=root
DB_PASSWORD=
```

> XAMPP default: username=root, password=kosong

Ubah juga APP_URL:
```env
APP_URL=http://localhost/lms-smk-tunas-harapan
```

### STEP 8: Generate Application Key
```bash
php artisan key:generate
```

### STEP 9: Run Migrations (Buat Tabel Database)
```bash
php artisan migrate
```

> Jika error, pastikan nama database di .env sudah benar,
> lalu jalankan: `php artisan migrate:fresh`

### STEP 10: Seed Data (Isi Data Contoh)
```bash
php artisan db:seed
```

Data yang akan terisi:
- 1 Admin
- 10 Guru
- 50 Siswa
- 9 Kelas (PPLG X-1 s/d XII-2, TJKT X-1 s/d XI-1)
- 21 Mata Pelajaran
- 35 Materi
- 25 Tugas
- 120+ Pengumpulan Tugas

### STEP 11: Buat Storage Link
```bash
php artisan storage:link
```

### STEP 12: Jalankan Server!
```bash
php artisan serve
```

Atau spesifik port:
```bash
php artisan serve --port=8080
```

### STEP 13: Buka di Browser
```
http://127.0.0.1:8000
```

Akan otomatis redirect ke halaman login.

---

## LOGIN CREDENTIALS

| Role  | Email                      | Password    |
|-------|----------------------------|-------------|
| Admin | admin@smktunas.sch.id      | password    |
| Guru  | guru1@smktunas.sch.id      | password123 |
| Siswa | siswa1@smktunas.sch.id     | password123 |

---

## REKOMENDASI EKSTENSI VS CODE

Install ekstensi berikut untuk pengalaman developer yang lebih baik:

1. **Laravel Pint** (pa11y.laravel-pint) - Code formatter
2. **Laravel Extra Intellisense** (amiralioff.laravel-extra-intellisense) - Autocomplete
3. **Laravel Blade Snippets** (onecentlin.laravel-blade-snippets) - Blade autocomplete
4. **Tailwind CSS IntelliSense** (bradlc.vscode-tailwindcss) - Tailwind autocomplete
5. **PHP Intelephense** (bmewburn.vscode-intelephense) - PHP intelligence
6. **DotENV** (mikestead.dotenv) - .env file syntax
7. **Error Lens** (usernamehw.errorlens) - Inline error highlighting
8. **GitLens** (eamodio.gitlens) - Git history

---

## TROUBLESHOOTING

### Error: "PHP version not meet requirements"
XAMPP PHP lama? Download XAMPP versi terbaru:
https://www.apachefriends.org/

### Error: "SQLSTATE[HY000] [1049] Unknown database"
Database belum dibuat. Buat dulu di phpMyAdmin.

### Error: "No application encryption key"
Jalankan: `php artisan key:generate`

### Error: "storage link already exists"
Sudah OK, abaikan. Atau hapus dulu:
```
rmdir public\storage
php artisan storage:link
```

### Error: "Port 8000 already in use"
Ganti port:
```bash
php artisan serve --port=8081
```

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "419 Page Expired"
Hapus cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Halaman Blank / White Screen
Hapus semua cache:
```bash
php artisan optimize:clear
```

### Mau Reset Semua Data?
```bash
php artisan migrate:fresh --seed
```

---

## CARA ALTERNATIF: Tanpa "php artisan serve"

Jika ingin langsung akses via Apache XAMPP (tanpa perlu `php artisan serve`):

1. Pastikan project di `C:\xampp\htdocs\lms-smk-tunas-harapan`
2. Buat file `.htaccess` di dalam folder `public/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

3. Akses: `http://localhost/lms-smk-tunas-harapan/public`

4. Tapi lebih disarankan pakai `php artisan serve` karena lebih stabil.
