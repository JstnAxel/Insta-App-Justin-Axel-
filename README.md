# InstaApp - Laravel

## Deskripsi
InstaApp adalah aplikasi berbasis Laravel yang memungkinkan pengguna untuk membuat akun, login, membuat postingan dengan teks dan gambar, memberikan like, serta berkomentar pada postingan. Aplikasi ini menggunakan Blade sebagai templating engine tanpa framework frontend tambahan.

## Fitur
- Autentikasi pengguna (register, login, logout)
- CRUD Postingan dengan teks dan gambar
- Like dan Unlike postingan tanpa refresh
- Komentar dan balasan komentar dalam bentuk nested comments
- Hak akses berdasarkan pemilik postingan dan komentar

## Instalasi

1. Clone repositori ini:
   ```sh
   git clone https://github.com/username/reponame.git
   cd reponame
   ```

2. Install dependensi Laravel:
   ```sh
   composer install
   ```

3. Copy file `.env` dan sesuaikan konfigurasi database:
   ```sh
   cp .env.example .env
   ```

4. Generate application key:
   ```sh
   php artisan key:generate
   ```

5. Konfigurasi database di `.env`, lalu jalankan migrasi:
   ```sh
   php artisan migrate 
   ```

6. Jalankan server aplikasi:
   ```sh
   php artisan serve
   ```

7. Akses aplikasi di `http://localhost:8000`

## Teknologi yang Digunakan
- Laravel 11
- Blade Templating Engine
- MySQL / PostgreSQL (sesuai konfigurasi database)
- TailwindCSS (jika digunakan untuk styling)

## Cara Menjalankan Aplikasi
1. Jalankan perintah `php artisan serve`
2. Buka browser dan akses `http://localhost:8000`

## Cara Berkontribusi
1. Fork repositori ini.
2. Buat branch baru untuk fitur atau perbaikan bug Anda.
3. Commit perubahan Anda dengan pesan yang jelas.
4. Push branch ke GitHub dan buat pull request.

## Lisensi
Aplikasi ini dirilis di bawah lisensi MIT.

