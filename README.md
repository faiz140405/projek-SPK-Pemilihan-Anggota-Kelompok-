<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Aplikasi SPK Pemilihan Tim Proyek

Aplikasi Sistem Pendukung Keputusan (SPK) ini dirancang untuk membantu dalam proses pemilihan anggota tim proyek. Dengan menggunakan kriteria yang dapat disesuaikan dan sistem penilaian, aplikasi ini memberikan rekomendasi anggota tim berdasarkan metode perhitungan SPK.

## ✨ Fitur Utama

* 📝 **Manajemen Kriteria:** Pengguna dapat menambah, mengedit, dan mengelola daftar kriteria ...
* 👥 **Manajemen Anggota Tim:** Pengguna dapat menambah, mengedit, dan mengelola daftar anggota tim ...
* ⭐ **Input Penilaian:** Menyediakan antarmuka untuk memberikan nilai/skor ...
* 📊 **Hasil Rekomendasi:** Menampilkan peringkat anggota tim berdasarkan skor SPK ...
* 📈 **Dashboard Informatif:** Halaman utama yang menyajikan ringkasan statistik ...
* 🔑 **Sistem Autentikasi:** Fitur login dan registrasi pengguna ...

## 🛠️ Teknologi yang Digunakan

* 💻 **Backend:** Laravel (PHP Framework)
* 🗄️ **Database:** MySQL
* 🎨 **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
* 📊 **Charting Library:** Chart.js

## Instalasi dan Setup Proyek

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal di lingkungan pengembangan Anda.

1.  **Kloning Repositori:**
    Buka terminal atau Git Bash, lalu kloning repositori ini:
    ```bash
    git clone [https://github.com/faiz140405/projek-SPK-Pemilihan-Anggota-Kelompok-.git](https://github.com/faiz140405/projek-SPK-Pemilihan-Anggota-Kelompok-.git)
    cd projek-SPK-Pemilihan-Anggota-Kelompok-
    ```

2.  **Instal Dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Salin File Lingkungan dan Buat Kunci Aplikasi:**
    Laravel membutuhkan file `.env` untuk konfigurasi lingkungan.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Konfigurasi Database:**
    Buka file `.env` dengan editor teks Anda dan sesuaikan detail koneksi database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda  # Ganti dengan nama database yang Anda buat
    DB_USERNAME=username_database_anda # Ganti dengan username MySQL Anda (misal: root)
    DB_PASSWORD=password_database_anda # Ganti dengan password MySQL Anda (kosong jika tidak ada)
    ```
    **Penting:** Pastikan Anda telah membuat database dengan nama yang sama di server MySQL/MariaDB Anda (misalnya melalui phpMyAdmin atau MySQL Workbench).

5.  **Jalankan Migrasi Database:**
    Ini akan membuat tabel-tabel yang diperlukan di database Anda (pengguna, kriteria, anggota tim, penilaian).
    ```bash
    php artisan migrate
    ```
    *Jika Anda ingin menghapus semua tabel dan membuat ulang dari awal (misalnya saat *development* awal), Anda bisa gunakan `php artisan migrate:fresh`.*

6.  **Instal Dependensi NPM dan Kompilasi Aset Frontend:**
    Laravel menggunakan Vite (melalui NPM) untuk mengelola aset CSS dan JavaScript (seperti Tailwind CSS dan Alpine.js).
    ```bash
    npm install
    npm run dev
    ```
    **Catatan:** Biarkan perintah `npm run dev` berjalan di *terminal* terpisah selama Anda mengembangkan aplikasi, karena ini akan mengkompilasi ulang aset secara otomatis saat ada perubahan.

7.  **Jalankan Server Pengembangan Laravel:**
    ```bash
    php artisan serve
    ```

8.  **Akses Aplikasi:**
    Buka *browser* Anda dan kunjungi URL berikut:
    `http://127.0.0.1:8000`

    Anda akan melihat halaman *landing* Laravel. Anda bisa mendaftar akun baru dan mulai menggunakan fitur SPK.

## Cara Menggunakan Aplikasi

1.  **Daftar/Login:** Buat akun baru atau masuk ke akun yang sudah ada.
2.  **Input Kriteria:** Tambahkan kriteria penilaian beserta bobotnya (misalnya, "Keahlian Frontend" dengan bobot 8, "Keahlian Backend" dengan bobot 7, dst.).
3.  **Daftar Anggota Tim:** Tambahkan detail anggota tim yang akan dinilai.
4.  **Input Penilaian:** Berikan skor untuk setiap anggota tim di setiap kriteria.
5.  **Lihat Hasil SPK:** Sistem akan menghitung skor akhir dan menampilkan peringkat anggota tim.
6.  **Dashboard:** Lihat ringkasan data dan grafik rekomendasi anggota tim teratas.

## Kontribusi

Kami menyambut kontribusi untuk proyek ini! Jika Anda ingin membantu, silakan ikuti langkah-langkah berikut:
1.  *Fork* repositori ini.
2.  Buat cabang baru untuk fitur Anda (`git checkout -b fitur/nama-fitur-anda`).
3.  Lakukan perubahan dan *commit* (`git commit -m "Deskripsi perubahan"`).
4.  Dorong perubahan ke cabang Anda (`git push origin fitur/nama-fitur-anda`).
5.  Buat *Pull Request* baru.

---
Dikembangkan oleh: **Faiz Nizar Nu'aim**
If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
