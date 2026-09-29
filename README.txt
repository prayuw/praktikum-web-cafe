MONSTERA CAFE - TEMPLATE PRAKTIKUM

Template ini mengikuti pola project dosen:
- PHP native + MySQL/MariaDB
- Session login admin
- CRUD data utama
- Upload gambar
- Search/filter
- Pagination pada kelola menu

Modifikasi:
- Produk -> Menu
- Menambahkan fitur Galeri
- Folder admin/menu dan admin/galeri
- Folder uploads/menu dan uploads/galeri
- Tampilan CSS soft green

CARA MENJALANKAN
1. Letakkan folder praktikum_web_cafe di htdocs/Laragon www.
2. Buat database dengan mengimpor toko_kue.sql melalui phpMyAdmin.
3. Pastikan database di config/database.php bernama web_cafe.
4. Jalankan Apache dan MySQL.
5. Buka /praktikum_web_cafe/
6. Login admin di /praktikum_web_cafe/auth/login.php
   Username: admin
   Password: admin123

PENTING
Folder uploads/menu dan uploads/galeri harus dapat ditulis oleh server.
