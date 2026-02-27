# 🎓 Student Portfolio Management System  
**Laravel 12 • MySQL • Modern Web Application**

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?logo=laravel&logoColor=white&style=for-the-badge" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-^8.2-777BB4?logo=php&logoColor=white&style=for-the-badge" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white&style=for-the-badge" alt="MySQL">
  <img src="https://img.shields.io/badge/Vite-Frontend%20Build-646CFF?logo=vite&logoColor=white&style=for-the-badge" alt="Vite">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License MIT">
</p>

<p align="center">
  <strong>Sistem manajemen portfolio mahasiswa</strong> yang powerful, mudah dikembangkan, dan siap produksi.<br>
  Cocok untuk universitas, politeknik, atau proyek akhir/skripsi berbasis web.
</p>


## Link Demo:
- <a href="https://trpl-polmind.com">Menuju website Portofolio_Mahasiswa 🔗</a>
- <a href="https://trpl-polmind.com">https://trpl-polmind.com 🔗</a>

## Judul Proyek: Portofolio_Mahasiswa

# ✨ Fitur Utama

- **Manajemen Mahasiswa** (profile, data pribadi, dll)
- **Manajemen Jurusan** & **Keahlian** (dengan relasi many-to-many)
- **Portfolio Mahasiswa** (upload proyek, deskripsi, link, gambar)
- **Learning Corner** (konten pembelajaran / tips / resources)
- **Authentication lengkap**:
  - Login dengan **username**
  - Remember Me (token)
  - Password Reset (email)
  - Session berbasis **database**
- **Responsive design** (siap mobile & desktop)
- **Seeder siap pakai** untuk demo cepat

## 🛠️ Teknologi Stack

- **Backend** : Laravel 12 (latest features 2025)
- **Database**: MySQL 8+
- **Frontend**: Blade + Tailwind CSS + Alpine.js (atau Vite + JS framework pilihan)
- **Session Driver**: Database
- **Package Manager**: Composer & npm

## 🚀 Cara Instalasi (Local Development)

1. **Clone repository**

   ```bash
   git clone https://github.com/GisatAZK2/Portofolio_Mahasiswa
   cd Portofolio_Mahasiswa
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

   Menggunakan frontend build (Vite):

   ```bash
   npm install && npm run build
   ```

3. **Siapkan environment**

   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi `.env`**

   Sesuaikan bagian database:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=student_portfolio
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Jalankan migrasi + seeder**

   ```bash
   php artisan migrate --seed
   ```

   Atau fresh install:

   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan server**

   ```bash
   php artisan serve
   ```

   Buka: http://127.0.0.1:8000

   **Akun demo default**:
   - Username: `root`  
   - Password: ` `

## 📊 Struktur Tabel Utama

| Tabel                   | Deskripsi                              |
|-------------------------|----------------------------------------|
| `users`                 | Data autentikasi & mahasiswa           |
| `jurusan`               | Daftar jurusan                         |
| `keahlian`              | Daftar skill/keahlian                  |
| `keahlian_user`         | Pivot table many-to-many               |
| `projects`              | Kumpulan karya/proyek mahasiswa        |
| `learning_corner`       | Konten edukasi / tips                  |
| `sessions`              | Database session driver                |
| `password_reset_tokens` | Token reset password                   |

## 🧹 Maintenance Commands

Clear cache ketika ada masalah:

```bash
php artisan optimize:clear
```

Reset total database + isi ulang demo data:

```bash
php artisan migrate:fresh --seed
```

## 🔐 Fitur Keamanan & Best Practice

- Hash password dengan bcrypt
- Rate limiting pada login
- Session database (lebih aman & scalable)
- CSRF protection (default Laravel)
- Validation di setiap form

## 📄 Lisensi

MIT License – bebas digunakan, dimodifikasi, dan didistribusikan untuk keperluan pribadi maupun komersial.

## ❤️ Dibuat dengan

Laravel 12 & semangat belajar!

Developed with ❤️ by **Team Kasih Sayang**

---
