# README - Panduan Penggunaan Aplikasi eCommerce

## Persyaratan Awal
1. Pastikan Anda telah menginstal aplikasi **XAMPP** atau **WAMP** untuk menjalankan server lokal.
2. Aplikasi ini menggunakan database dengan nama **ecommerce_db**. Anda harus membuat database lalu mengimpor file SQL database (ecommerce_db) yang ada pada file sebelum menjalankan aplikasi.

## Langkah-Langkah Instalasi

1. **Ekstrak Aplikasi**
   - Ekstrak folder aplikasi ke direktori server lokal Anda:
     - Untuk XAMPP: `htdocs`
     - Untuk WAMP: `www`

2. **Impor Database**
   - Buka **phpMyAdmin** melalui browser Anda (biasanya diakses melalui `http://localhost/phpmyadmin`).
   - Buat database baru dengan nama `ecommerce_db`.
   - Pilih database tersebut, kemudian klik tombol **Import**.
   - Pilih file SQL yang disediakan (`ecommerce_db.sql`) dan klik **Go**.

3. **Konfigurasi Koneksi Database**
   - Buka file konfigurasi database pada aplikasi `/includes/db.php`.
   - Pastikan detail koneksi database Anda sesuai:
     ```php
    $host = 'localhost';  
    $db = 'ecommerce_db'; 
    $user = 'root';       
    $pass = '';  
     ```

## Role Login
Aplikasi ini memiliki 3 role pengguna dengan kredensial berikut:

### Role User
- **Username**: user
- **Password**: user123

### Role UMKM
- **Username**: umkm
- **Password**: umkm123

### Role Admin
- **Username**: admin
- **Password**: admin123

## Cara Mengakses Aplikasi
1. Buka browser Anda dan akses aplikasi melalui URL berikut:
   - `http://localhost/nama-folder-aplikasi/login.php`
   *(Ganti `nama-folder-aplikasi` dengan nama folder tempat aplikasi disimpan)*
2. Masukkan username dan password sesuai dengan role yang Anda miliki untuk login ke dalam sistem.
3. Aplikasi menggunakan session, jika anda berusaha mengakses halaman admin/umkm dan sesi login anda tidak terdeteksi maka anda akan diarahkan lagi ke login.php untuk melakukan verifikasi login sesuai dengan role anda

## Fitur Aplikasi
- **User**:
  - Melihat produk.
  - Melakukan pemesanan produk.
- **UMKM**:
  - Mengelola produk (tambah, ubah, hapus).
  - Melihat pesanan.
- **Admin**:
  - Mengelola pengguna.
  - Mengelola UMKM.
  - Melihat laporan.

## Masalah dan Bantuan
Jika Anda mengalami masalah dalam menggunakan aplikasi, pastikan Anda telah:
- Mengimpor database dengan benar.
- Mengonfigurasi koneksi database dengan benar di file konfigurasi.
- Menggunakan kredensial login yang sesuai.

Jika masalah masih terjadi, silakan hubungi Developer https://wa.me/6281242818675.
