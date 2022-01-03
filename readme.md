## Aplikasi Point of Sale Laravel Sederhana

## Fitur
Fitur yang ada di aplikasi ini sederhana yaitu meliputi proses penjualan dan pembelian produk. Untuk fungsinya sebagai berikut:

## Navigasi
Berikut adalah navigasi yang ada dalam aplikasi POS sederhana ini :

- **Admin**
    - Dashboard
    - Pengguna
    - Laporan
        -Penjualan
        -Pembelian
    - Setting
- **Petugas**
    - Dashboard
    - Master
        -Kategori
        -Supplier
        -Produk
    - Stok
    - Transaksi
        -Penjualan
        -Pembelian
    - Pembeli
    
## Bahasa dan Alat
Bahasa dan alat-alat apa yang dibutuhkan dalam pembuatan aplikasi ini berupa :

- XAMPP
- PHP
- JQuery
- Framework Laravel 5.8
- Template AdminLTE
- SweetAlert2
- Select2
- lightboxjs
- fontawesome
- etc.

## Cara Install
Untuk cara install aplikasi ini kepada komputer kamu dengan cara :

- git clone atau download repo ini
- buka code editor lalu open project aplikasi ini.
- buat file **.env** di folder, lalu isikan nama databasenya sesuai dengan database yang kamu buat.
- buka command line (disini saya gunakan git bash, atau bisa juga command prompt) arahkan tujuan ke folder project ini yang sudah diinstal tadi.
- jalankan perintah **php artisan key:generate**.
- jalankan perintah **composer install** untuk me-load dependencies yang dipakai aplikasi ini.
- ketikkan **php artisan migrate**, untuk memuat tabel ke dalam database dan tunggu sampai selesai.
- lalu ketikkan **php artisan db:seed** untuk memuat data default terhadap table yang sudah kita buat dari perintah **migrate** tadi.
- aktifkan xampp, lalu buka browser dengan url **localhost:8000**.
- akan muncul halaman login jika berhasil, lalu masukkan username dan password.
- untuk login menjadi admin dengan ketikkan **username: admin** dan **password: 123**.
- untuk login menjadi petugas dengan ketikkan **username: petugas** dan **password: 123**.

