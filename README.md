<div align="center">
  <img src="https://github.com/BevanTri/bvs-untirta/raw/main/public/images/logo-untirta.webp" alt="BVS Untirta Logo" width="120" style="background: #0F172A; padding: 20px; border-radius: 16px;">

  # 🔧 BVS Bengkel Untirta
  ### Sistem Informasi Bengkel Terpadu

  **Platform E-Commerce Sparepart, Workshop Management, Online Booking & Customer Portal**

  [![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Sanctum API](https://img.shields.io/badge/Sanctum_API-Bearer_Token-000000?style=for-the-badge&logo=json-web-tokens&logoColor=white)](https://laravel.com/docs/sanctum)
  [![iPaymu](https://img.shields.io/badge/Payment-iPaymu-00A859?style=for-the-badge&logo=payment&logoColor=white)](https://ipaymu.com)
  [![License MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

  [**Quick Start**](#-quick-start-local-setup) • [**Documentation**](#-documentation-index) • [**API Contract**](#-restful-api-endpoints) • [**Testing Suite**](#-qa--blackbox-testing)

</div>

---

## 📌 About BVS Bengkel Untirta

**BVS (Bengkel Virtual Shop) Bengkel Untirta** adalah platform web terpadu untuk digitalisasi operasional bengkel otomotif serta e-commerce sparepart. Dibangun menggunakan **Laravel 13**, **Eloquent ORM**, dan antarmuka **Mobile-First Responsive Design**, sistem ini menghubungkan tim internal bengkel (Admin) dengan pelanggan secara terpusat.

### 🌟 Keunggulan & Nilai Utama
- 🛒 **E-Commerce Sparepart**: Katalog 265+ produk (Ban, Aki, Oli, Shock Absorber) dengan keranjang belanja persisten dan checkout via iPaymu.
- 🔧 **Workshop Management**: Pelacakan repair order real-time (`menunggu` ➔ `proses` ➔ `selesai`) lengkap dengan sparepart tracking dan invoice compact.
- 👥 **Manajemen Pelanggan & Kendaraan**: Pencatatan terpusat yang menghubungkan pemilik dengan kendaraan terdaftar, validasi unik nomor polisi.
- 💳 **Pembayaran Terpadu**: Payment polymorphic untuk order produk + repair order via iPaymu Virtual Account (sandbox).
- 📊 **Laporan & Analitik**: Filter periode (harian/mingguan/bulanan/tahunan) dengan export CSV delimiter titik koma.
- 🔐 **Dual Authentication**: Auth terpisah untuk Admin (Laravel Breeze) dan Customer, dilengkapi Sanctum API token.
- 📱 **Mobile-First**: Touch-friendly (min 44px), overflow-safe di semua HP, dark mode dengan View Transition API.

---

## 🛠️ Technology Stack

| Layer | Teknologi | Deskripsi |
|:---|:---|:---|
| **Framework** | **Laravel 13.x** | PHP Web Framework dengan Arsitektur MVC |
| **Bahasa** | **PHP 8.3** / **JavaScript (Vanilla)** | Logika Backend & Interaktivitas Frontend |
| **Database** | **MySQL 8.0** | Basis Data Relasional (InnoDB, Foreign Key & Indexing) |
| **ORM** | **Eloquent ORM** | 100% Prepared Statements (Zero Raw SQL Injection Risk) |
| **Frontend** | **Tailwind CSS 3.x** + **Vite** | Custom Design System, Mobile-First Responsive |
| **Payment** | **iPaymu** (cURL) | Virtual Account, Sandbox Mode |
| **Autentikasi** | **Laravel Breeze** & **Sanctum** | Session-based web auth & Bearer Token API auth |
| **API** | **RESTful API** | Respon JSON standar untuk konsumsi data eksternal |
| **Theme** | **View Transition API** | Dark/Light mode dengan animasi circle-reveal |
| **Hosting** | **InfinityFree** | Free hosting with PHP 8.3 + MySQL |

---

## ✨ Fitur Unggulan

### 🛒 E-Commerce Sparepart
- **Katalog Produk** — 265+ produk (Ban, Aki, Oli, Shock Absorber) dengan filter kategori dan brand partner
- **Keranjang Belanja** — Add/remove/qty via vanilla JS, persisten per user
- **Checkout iPaymu** — Pembayaran Virtual Account (sandbox) dengan callback otomatis
- **Invoice Compact** — Format 420px profesional, siap print (seperti invoice Tokopedia/Shopee)
- **Export CSV** — Export data order ke Excel (separator `;`, BOM UTF-8 untuk Excel Indonesia)

### 🔧 Workshop Management
- **Repair Order** — Status lifecycle: `menunggu` ➔ `proses` ➔ `selesai`/`dibatalkan`
- **Sparepart Tracking** — Setiap sparepart yang dipakai tercatat (nama, qty, harga), stok otomatis berkurang
- **Mekanik Assignment** — Penugasan mekanik dengan spesialisasi ke repair order
- **Payment Sync** — Status pembayaran sinkron otomatis dengan iPaymu callback
- **Conversion to Sales** — Sparepart otomatis tercatat sebagai transaksi penjualan saat admin selesaikan servis

### 👨‍💼 Admin Panel
- **Dashboard** — Widget statistik, grafik revenue, recent orders
- **CRUD Lengkap** — Produk, Kategori, Service, Brand, Pelanggan, Kendaraan, Mekanik, Pesanan
- **Laporan** — Filter periode dengan revenue produk & servis terpisah
- **Dark Mode** — Toggle tema dengan View Transition API circle-reveal

### 👤 Customer Portal
- **Cek Status Servis** — Lacak repair order via nomor polisi
- **Riwayat Pesanan** — History pembelian produk + servis dalam satu halaman
- **Invoice & Nota** — Cetak invoice/nota digital

---

## ⚡ Quick Start (Local Setup)

### Prasyarat System
- **PHP** >= 8.2 (Ekstensi: `pdo`, `mbstring`, `openssl`, `gd`, `bcmath`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm**
- **MySQL** >= 8.0

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/BevanTri/bvs-untirta.git
cd bvs-untirta

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment & generate application key
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bvs_bengkel
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migrasi database & seeder data awal
php artisan migrate --seed

# 7. Buat symbolic link untuk media upload
php artisan storage:link

# 8. Build aset frontend
npm run build

# 9. Jalankan server lokal
php artisan serve
```

Akses aplikasi di `http://127.0.0.1:8000`.

---

## 🔑 Kredensial Akun Pengujian (Default)

| Role | Email | Password | Hak Akses |
|:---|:---|:---:|:---|
| **Admin** | `admin@bengkel.test` | `password` | Akses Penuh & Full CRUD |
| **Customer** | `customer@bengkel.test` | `password` | Portal Pelanggan, Booking & Belanja |

---

## 🌐 Endpoints RESTful API

BVS Bengkel Untirta menyediakan REST API yang diamankan menggunakan **Laravel Sanctum** (Bearer Token):

| No | Method | Endpoint | Auth | Deskripsi |
|:--:|:---:|:---|:---:|:---|
| 1 | `POST` | `/api/token` | Public | Autentikasi & terbitkan Sanctum Bearer Token |
| 2 | `POST` | `/api/logout` | Bearer | Revoke token Sanctum aktif |
| 3 | `GET` | `/api/services` | Bearer | Riwayat repair order (dengan pagination & search) |
| 4 | `GET` | `/api/customers` | Bearer | Daftar pelanggan |

---

## 🧪 QA & Blackbox Testing

BVS Bengkel Untirta telah melewati pengujian **Blackbox Testing** komprehensif mencakup **137 test cases** terverifikasi.

- 📄 **Dokumen Hasil Pengujian**: [`docs/blackbox.md`](docs/blackbox.md) (137 Test Cases ✅ Pass)
- 📋 **Template CSV**: [`docs/test-case.csv`](docs/test-case.csv) (siap import Word Mail Merge)

---

## 📖 Indeks Dokumentasi (Single Source of Truth)

Dokumen teknis, arsitektur, dan panduan presentasi tersedia di folder `docs/`:

| No | Dokumen | Path | Deskripsi |
|:--:|:---|:---|:---|
| 1 | **PRD Utama (SSOT)** | [`docs/PRD_BVS_Bengkel_Untirta.md`](docs/PRD_BVS_Bengkel_Untirta.md) | Single Source of Truth untuk ERD, RBAC & API Contract |
| 2 | **Design System** | [`docs/DESIGN.md`](docs/DESIGN.md) | Tokens warna, tipografi & komponen UI |
| 3 | **Panduan Presentasi** | [`docs/penjelasan.md`](docs/penjelasan.md) | Penjelasan teknis untuk sidang/presentasi |
| 4 | **ERD** | [`docs/erd.md`](docs/erd.md) | Entity Relationship Diagram & Data Dictionary |
| 5 | **Blackbox Test Suite** | [`docs/blackbox.md`](docs/blackbox.md) | 137 test cases terverifikasi |
| 6 | **Execution Playbook** | [`docs/guidebook.md`](docs/guidebook.md) | Guidebook pelaksanaan & pengerjaan |

---

## 📁 Struktur Direktori Proyek

```
bvs-untirta/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller Web, Admin, dan API
│   │   │   ├── Admin/         # Admin-specific Controllers
│   │   │   └── Api/           # API Controllers (JSON Response)
│   │   └── Middleware/        # Middleware RBAC & Auth
│   ├── Models/                # Eloquent Models & Relasi Database
│   └── Services/              # Business Logic Services
├── config/                    # Konfigurasi Aplikasi
├── database/
│   ├── migrations/            # File Migrasi Terstruktur
│   └── seeders/               # Data Awal & Default Seeder
├── docs/                      # PRD, Architecture & Dokumentasi QA
├── public/                    # Public Assets & Logo
│   └── images/                # Gambar, logo, uploads
├── resources/
│   ├── css/                   # Tailwind CSS Styles
│   ├── js/                    # Vanilla JavaScript
│   └── views/                 # Template Blade (Admin, Customer, Public)
├── routes/
│   ├── api.php                # Rute RESTful API (Sanctum)
│   ├── auth.php               # Rute Autentikasi
│   └── web.php                # Rute Navigasi Web Utama
└── Template/                  # Dokumentasi Akademik Tambahan
```

---

## 👥 Tim Pengembang

Disusun untuk Mata Kuliah **Pemrograman Web & Sistem Basis Data** — UAS Semester Antara 2025/2026:

| Anggota | NPM | Peran |
|:---|:---:|:---|
| **Bevan Tri Ramadiyas** | 3337250063 | Fullstack Developer & Software Architect |
| **Fauzi Nur Ibrahim** | 3337250019 | Backend & Database Engineer |

---

<div align="center">

  **BVS Bengkel Untirta** • Built with ❤️ using [Laravel](https://laravel.com) and [Tailwind CSS](https://tailwindcss.com)  
  🌐 [bvsuntirta.rf.gd](https://bvsuntirta.rf.gd/)  
  *© 2026 BVS Team. All rights reserved.*

</div>
