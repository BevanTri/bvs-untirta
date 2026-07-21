<p align="center">
  <img src="https://github.com/BevanTri/bvs-untirta/raw/main/public/images/logo-untirta.webp" width="120" alt="BVS Untirta Logo" style="background: #0F172A; padding: 20px; border-radius: 16px;">
</p>

<h1 align="center">BVS Bengkel Untirta</h1>

<p align="center">
  Website bengkel mobil dan motor resmi BVS (Bengkel Virtual Shop) — Universitas Tirtayasa<br>
  Proyek UAS Semester Antara 2025/2026
</p>

<p align="center">
  🌐 <a href="http://bvsuntirta.rf.gd/"><strong>bvsuntirta.rf.gd</strong></a>
</p>

---

## 📌 Tentang

**BVS Bengkel Untirta** adalah platform e-commerce bengkel mobil dan motor yang dibangun menggunakan Laravel 13. Website ini menyediakan layanan pemesanan produk (ban, aki, oli, shock absorber) dan jasa servis secara online.

Dibuat sebagai proyek **Ujian Akhir Semester** (UAS) Semester Antara 2025/2026 — Universitas Tirtayasa.

---

## ✨ Fitur

### 🛒 E-Commerce
- **Katalog Produk** — Ban, Aki, Oli, Shock Absorber (265+ produk)
- **Layanan Servis** — 9 jasa bengkel dengan harga tetap
- **Keranjang Belanja** — Add/remove/qty via vanilla JS
- **Checkout & Pembayaran iPaymu** — Virtual Account (sandbox)
- **Invoice & Nota** — Cetak invoice/nota per order
- **Export CSV** — Export data order ke Excel (separator `;`, BOM untuk Excel Indonesia)

### 🔧 Workshop Management
- **Data Pelanggan** — Riwayat kendaraan & servis per customer
- **Data Kendaraan** — Multi-vehicle per customer (plat, merk, model, tahun)
- **Data Mekanik** — Manajemen mekanik dengan spesialisasi
- **Repair Order** — Status: menunggu → proses → selesai/dibatalkan
- **Sparepart Tracking** — Setiap sparepart yang dipakai tercatat (nama, qty, harga)
- **Pembayaran Terpadu** — Payment polymorphic (order produk + repair order)
- **Laporan** — Filter per period (harian/mingguan/bulanan/tahunan)

### 📱 Lainnya
- **🔐 Auth** — Login/register, role admin & customer
- **📱 Mobile-First Responsive** — Tailwind CSS, touch-friendly (min 44px), overflow-safe di semua HP
- **📡 REST API** — Sanctum token auth untuk integrasi eksternal
- **👨‍💼 Admin Panel** — CRUD semua entitas, inline edit, dashboard with chart
- **🧾 Combined Order History** — Riwayat pesanan produk + servis dalam satu halaman

---

## 🛠️ Tech Stack

| Bagian     | Teknologi                                          |
| ---------- | --------------------------------------------------- |
| Backend    | PHP 8.3, Laravel 13, MySQL                          |
| Frontend   | Tailwind CSS, Vanilla JS, Vite                      |
| Payment    | iPaymu (Virtual Account via raw cURL)               |
| Hosting    | InfinityFree (Free hosting)                         |
| Auth       | Laravel Breeze, Laravel Sanctum (API tokens)        |
| API        | RESTful, Sanctum token auth, JSON Resources         |

---

## 🚀 Live URL

[http://bvsuntirta.rf.gd/](http://bvsuntirta.rf.gd/)

---

## 📦 Instalasi Lokal

```bash
git clone https://github.com/BevanTri/bvs-untirta.git
cd bvs-untirta
composer install
cp .env.example .env
# konfigurasi DB di .env (MySQL atau SQLite)
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

---

## 📡 API Endpoints

| Method | Endpoint           | Auth         | Deskripsi                      |
| ------ | ------------------ | ------------ | ------------------------------ |
| POST   | `/api/token`       | —            | Dapatkan token (email/password/device_name) |
| GET    | `/api/services`    | Bearer Token | Daftar layanan servis          |
| GET    | `/api/customers`   | Bearer Token | Daftar pelanggan               |

## 🔑 Test Credentials

| Role    | Email                  | Password   |
| ------- | ---------------------- | ---------- |
| Admin   | admin@bengkel.test     | password   |
| Customer| customer@bengkel.test  | password   |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik — Universitas Tirtayasa.
