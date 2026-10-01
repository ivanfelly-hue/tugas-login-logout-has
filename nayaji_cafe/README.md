# NayaJi Cafe

Aplikasi cafe PHP Native dan MySQL dengan PDO, katalog publik, pencarian, filter kategori, pagination, detail produk, serta dashboard CRUD admin.

## Struktur project

```text
nayaji_cafe/
|-- admin/
|   |-- edit.php
|   |-- hapus.php
|   |-- index.php
|   |-- konten.php
|   |-- login.php
|   |-- logout.php
|   |-- produk.php
|   |-- proses_konten.php
|   |-- proses_produk.php
|   |-- setup_admin.php
|   `-- tambah.php
|-- assets/
|   `-- style.css
|-- config/
|   `-- database.php
|-- includes/
|   |-- admin_nav.php
|   |-- auth.php
|   |-- flash.php
|   |-- footer.php
|   |-- form_produk.php
|   |-- functions.php
|   |-- header.php
|   `-- product_card.php
|-- uploads/
|   |-- produk/
|   |   |-- .htaccess
|   |   `-- .gitkeep
|   `-- website/
|       |-- .htaccess
|       `-- .gitkeep
|-- database.sql
|-- detail.php
|-- index.php
|-- produk.php
|-- tentang.php
`-- README.md
```

## Isi file

- `database.sql`: membuat/memigrasikan tabel `produk` (termasuk `featured`), `admin_users`, `website_content`, serta lima contoh menu.
- `config/database.php`: konfigurasi dan koneksi PDO MySQL.
- `index.php`, `produk.php`, `detail.php`, `tentang.php`: landing page, katalog, detail menu, dan cerita cafe.
- `admin/index.php` dan `admin/produk.php`: ringkasan dashboard dan daftar produk admin.
- `admin/tambah.php`, `admin/edit.php`, `admin/proses_produk.php`, `admin/hapus.php`: formulir dan proses CRUD dengan prepared statement, validasi, dan upload.
- `admin/konten.php` dan `admin/proses_konten.php`: editor data hero, About, kontak, CTA, dan foto website.
- `admin/login.php`, `admin/logout.php`, `admin/setup_admin.php`: autentikasi, logout, dan pembuatan akun admin pertama.
- `includes/auth.php`: proteksi admin dan token CSRF.
- `includes/flash.php`: flash message berbasis `$_SESSION['flash']`.
- `includes/functions.php`, `includes/header.php`, `includes/footer.php`, dan `includes/form_produk.php`: helper tampilan dan template bersama.
- `uploads/produk/` dan `uploads/website/`: foto produk serta foto hero/About yang diunggah admin.
- `assets/style.css`: tampilan responsive dengan palet ungu, pink, dan biru yang tersedia pada project workspace.

## Menjalankan dengan XAMPP

1. Salin folder `nayaji_cafe` ke `C:\xampp\htdocs\nayaji_cafe`.
2. Buka XAMPP Control Panel, lalu jalankan **Apache** dan **MySQL**.
3. Buka `http://localhost/phpmyadmin/`.
4. Pilih tab **Import**, pilih file `database.sql`, lalu tekan **Import** atau **Go**. File ini membuat database `nayaji_cafe`, memigrasikan kolom `featured` bila belum ada, membuat tabel `website_content`, dan mengisi contoh data tanpa menghapus data produk/admin yang sudah tersimpan.
5. Buka `config/database.php`. Konfigurasi default menggunakan host `localhost`, user `root`, password kosong, dan database `nayaji_cafe`. Sesuaikan user/password jika MySQL lokal Anda berbeda.
6. Pastikan ekstensi `pdo_mysql` dan `fileinfo` aktif di PHP. Jika pengaturan upload di `php.ini` lebih kecil, gunakan `upload_max_filesize = 2M` dan `post_max_size = 8M`, lalu restart Apache.
7. Buka `http://localhost/nayaji_cafe/` untuk halaman publik.
8. Buka `http://localhost/nayaji_cafe/admin/setup_admin.php` untuk membuat akun admin pertama. Buat username dan password yang kuat; password disimpan menggunakan `password_hash()`.
9. Login melalui `http://localhost/nayaji_cafe/admin/login.php`. Setelah admin pertama dibuat, halaman setup tidak menerima pembuatan akun tambahan.

Folder `uploads/produk/` dan `uploads/website/` harus dapat ditulisi oleh Apache. Upload hanya menerima JPG/JPEG/PNG valid dengan ukuran maksimal 2 MB. Hindari menghapus file `.htaccess` di folder tersebut.

## Fitur utama

- Katalog mengambil produk langsung dari tabel MySQL, dengan pencarian nama `LIKE`, filter kategori, dan pagination 10 item per halaman (`COUNT`, `LIMIT`, `OFFSET`).
- Semua query yang menggunakan input pengguna memakai PDO prepared statement. Output dari database di-escape menggunakan `htmlspecialchars()`.
- Proses tambah, edit, dan hapus memakai pola POST/Redirect/GET, pesan flash, autentikasi admin, serta token CSRF.
- Foto upload diperiksa ukuran dan MIME-nya, diberi nama acak, dan file lama dibersihkan setelah foto baru berhasil disimpan.
- Centang **Tampilkan di Menu Favorit** saat menambah/mengedit produk agar produk tampil pada landing page. Maksimal tiga favorit ditampilkan.
- Editor **Konten Website** mengubah hero, CTA, About, WhatsApp, email, Instagram, alamat, dan jam buka dari satu record database yang langsung dibaca halaman publik.
- Produk contoh menggunakan foto Unsplash; koneksi internet diperlukan untuk menampilkan foto eksternal tersebut. Foto yang diunggah admin disimpan lokal.

## Catatan desain

Workspace awal yang tersedia hanya berisi project Velora dan tidak memiliki file logo atau screenshot NayaJi Cafe. Karena itu project baru memakai wordmark teks “NayaJi Cafe” dan mengambil palet/typografi stylesheet yang tersedia sebagai acuan sementara. Folder project Velora tidak diubah.