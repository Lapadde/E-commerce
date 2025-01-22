# README - Panduan Penggunaan Aplikasi eCommerce

## 📋 Persyaratan Awal
1. Pastikan Anda telah menginstal aplikasi **XAMPP** atau **WAMP** untuk menjalankan server lokal.
2. Aplikasi ini menggunakan database dengan nama **ecommerce_db**. Anda harus mengimpor file SQL database sebelum menjalankan aplikasi.

---

## 🚀 Langkah-Langkah Instalasi

### 1. **Ekstrak Aplikasi**
- Ekstrak folder aplikasi ke direktori server lokal Anda:
  - Untuk XAMPP: `htdocs`
  - Untuk WAMP: `www`

### 2. **Impor Database**
- Buka **phpMyAdmin** melalui browser Anda (biasanya diakses melalui `http://localhost/phpmyadmin`).
- Buat database baru dengan nama `ecommerce_db`.
- Pilih database tersebut, kemudian klik tombol **Import**.
- Pilih file SQL yang disediakan (`ecommerce_db.sql`) dan klik **Go**.

### 3. **Konfigurasi Koneksi Database**
- Buka file konfigurasi database pada aplikasi (misalnya, `config.php`).
- Pastikan detail koneksi database Anda sesuai:
  ```php
  $host = 'localhost';
  $username = 'root'; // Sesuaikan jika berbeda
  $password = '';     // Kosongkan jika default, sesuaikan jika berbeda
  $dbname = 'ecommerce_db';
  ```

---

## 🔐 Role Login
Aplikasi ini memiliki 3 role pengguna dengan kredensial berikut:

| **Role** | **Username** | **Password** |
|----------|--------------|--------------|
| User     | user         | user123      |
| UMKM     | umkm         | umkm123      |
| Admin    | admin        | admin123     |

---

## 🌐 Cara Mengakses Aplikasi
1. Buka browser Anda dan akses aplikasi melalui URL berikut:
   ```
   http://localhost/nama-folder-aplikasi
   ```
   *(Ganti `nama-folder-aplikasi` dengan nama folder tempat aplikasi disimpan)*
2. Masukkan username dan password sesuai dengan role yang Anda miliki untuk login ke dalam sistem.

---

## ✨ Fitur Aplikasi

### **1. User**
- Melihat produk.
- Melakukan pemesanan produk.

### **2. UMKM**
- Mengelola produk (tambah, ubah, hapus).
- Melihat pesanan.

### **3. Admin**
- Mengelola pengguna.
- Mengelola UMKM.
- Melihat laporan.

---

## ❓ Masalah dan Bantuan
Jika Anda mengalami masalah dalam menggunakan aplikasi, pastikan Anda telah:
- Mengimpor database dengan benar.
- Mengonfigurasi koneksi database dengan benar di file konfigurasi.
- Menggunakan kredensial login yang sesuai.

Jika masalah masih terjadi, silakan hubungi pengembang aplikasi, **Taufiq Hidayat**, melalui [WhatsApp](https://wa.me/6281242818675) untuk bantuan lebih lanjut.

---

**💡 Catatan:** Pastikan semua langkah diikuti dengan benar untuk menghindari kesalahan saat menjalankan aplikasi.
