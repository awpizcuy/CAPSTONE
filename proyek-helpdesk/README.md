# 🛠️ PROYEK CAPSTONE: HELPDESK SYSTEM (Sistem Pelaporan Tiket Multi-Role)

## 🌟 Deskripsi Proyek

**Helpdesk System** adalah aplikasi web modern yang dirancang untuk mengelola siklus hidup penuh dari sebuah laporan atau tiket insiden, mulai dari pengajuan hingga penyelesaian, penilaian, dan dokumentasi.

Dibangun dengan arsitektur Model-View-Controller (MVC) menggunakan **Laravel**, sistem ini menerapkan otorisasi ketat berbasis peran (*Role-Based Access Control* - RBAC) untuk memisahkan fungsionalitas bagi tiga aktor utama: Admin Gedung, Kepala IT, dan Teknisi.

## 🚀 Tech Stack dan Dependencies

### ⚙️ Backend (PHP)
Aplikasi ini dibangun di atas fondasi PHP modern, memanfaatkan fitur dan performa terbaru.

| Komponen | Versi | Rasionalitas/Fungsi | Sumber |
| :--- | :--- | :--- | :--- |
| **Framework** | Laravel `^12.0` | Menyediakan arsitektur MVC yang solid dan fitur bawaan yang lengkap. | |
| **Bahasa Pemrograman** | PHP `^8.2` | Memanfaatkan peningkatan kinerja dan fitur keamanan terbaru. | |
| **API Authentication** | `laravel/sanctum ^4.2` | Digunakan untuk mengelola token API, mendukung otentikasi sesi berbasis cookie yang aman. | |
| **ORM & Database** | Eloquent (Bawaan Laravel) | Abstrak data SQL, menyediakan akses basis data yang intuitif dan aman. | |
| **Testing Framework** | `pestphp/pest ^3.8` | Digunakan untuk Feature dan Unit Testing yang cepat dan ekspresif. | |
| **Code Style Checker**| `laravel/pint ^1.24` | Alat untuk memastikan konsistensi dan standar gaya kode. | |

### 🎨 Frontend (JavaScript/CSS)
| Komponen | Versi/Type | Rasionalitas/Fungsi | Sumber |
| :--- | :--- | :--- | :--- |
| **Build Tool** | Vite | Menggantikan Laravel Mix, menawarkan kecepatan *development* yang lebih baik. | |
| **CSS Framework** | Tailwind CSS `^3.4.1` | Utility-first CSS framework untuk membangun antarmuka dengan cepat dan responsif. | |
| **Dependency Management** | NPM | Mengelola *library* *frontend* seperti `axios` dan `vue`. | |

---

## 🔒 Autentikasi dan Otorisasi (Multi-Role)

### 1. Sistem Autentikasi
Aplikasi ini menggunakan paket **Laravel Breeze** sebagai *scaffolding* untuk mengimplementasikan fitur-fitur otentikasi standar, yang mencakup:

* **Login/Logout:** Ditangani oleh `AuthenticatedSessionController`.
* **Pendaftaran (Register):** Ditangani oleh `RegisteredUserController`.
* **Reset Kata Sandi:** Fitur *Forgot Password* dan *Reset Password* terpisah.

### 2. Otorisasi Berbasis Peran (RBAC)
Otorisasi diimplementasikan melalui kolom `role` pada tabel `users`.

* **Middleware `role`**: Semua rute yang dilindungi menggunakan *middleware* `role:nama_peran` untuk memastikan hanya pengguna yang sah yang dapat mengakses *dashboard* dan fungsionalitas tertentu. (Asumsi middleware `CheckRole.php` ada dan terdaftar).
* **Redirect Dashboard**: Setelah *login*, pengguna secara otomatis diarahkan ke *dashboard* yang sesuai dengan peran mereka:
    * `admin_gedung` → `admin.dashboard`
    * `kepala_it` → `kepala.dashboard`
    * `teknisi` → `teknisi.dashboard`

---

## 🗺️ Rancangan Basis Data Utama (Model & Relasi)

Logika inti aplikasi diimplementasikan melalui dua model Eloquent utama:

### 1. Model `User` (`App\Models\User`)
* **Atribut Kunci:** `name`, `email`, `password`, `role`.
* **Relasi:**
    * `reports()`: **One-to-Many** (User membuat banyak Laporan) menggunakan `user_id`.
    * `tasks()`: **One-to-Many** (Teknisi ditugaskan ke banyak Laporan) menggunakan `assigned_technician_id`.

### 2. Model `Report` (`App\Models\Report`)
* **Atribut Pelaporan:** `user_id` (reporter), `assigned_technician_id`, `kategori`, `deskripsi_pengajuan`, `foto_awal` (Multimedia).
* **Atribut Status/Proses:** `status`, `status_note`.
* **Atribut Time Tracking:** `start_time`, `end_time`, `duration_minutes` (untuk pekerjaan Teknisi).
* **Atribut Penilaian:** `rating`, `rating_feedback` (oleh Admin Gedung).
* **Relasi:**
    * `reporter()`: **Belongs To** User.
    * `technician()`: **Belongs To** User.
    * `resolution()`: **Has One** Resolution (Solusi/Foto Akhir).

---

## 🛠️ Instalasi Lokal

Aplikasi ini telah dikonfigurasi dengan *script* Composer untuk memudahkan proses *setup* dan *development*.

### Persyaratan
* PHP >= 8.2
* Composer
* Node.js (LTS) & NPM

### Langkah-langkah Instalasi

1.  **Clone Repositori:**
    ```bash
    git clone https://github.com/awpizcuy/CAPSTONE.git
    cd proyek-helpdesk
    ```

2.  **Jalankan Skrip Setup Penuh:**
    Gunakan skrip `setup` yang sudah didefinisikan dalam `composer.json` untuk menginstal semua dependensi, mengkonfigurasi file `.env`, dan menjalankan migrasi basis data.

    ```bash
    composer run setup
    # Perintah yang dijalankan: composer install, copy .env, key:generate, migrate --force, npm install, npm run build
    ```

### Menjalankan Aplikasi (Development)

Gunakan skrip `dev` yang menjalankan beberapa layanan sekaligus untuk lingkungan pengembangan yang lengkap:

```bash
composer run dev
# Perintah yang dijalankan: 
# 1. php artisan serve (Server Web)
# 2. php artisan queue:listen (Memproses Notifikasi/Job Asinkron)
# 3. php artisan pail (Live Monitoring Log)
# 4. npm run dev (Vite Live Reloading)
# Semua berjalan secara bersamaan dengan `npx concurrently`
