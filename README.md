<p align="center">
  <img src="https://raw.githubusercontent.com/BevanTri/bvs-untirta/main/public/images/logo-untirta.webp" width="120" alt="BVS Untirta Logo" style="background: #0F172A; padding: 20px; border-radius: 16px;">
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

- 🛒 **Katalog Produk** — Ban, Aki, Oli, Shock Absorber (265+ produk)
- 🔧 **Layanan Servis** — 9 jasa bengkel dengan harga tetap
- 🛍️ **Keranjang Belanja** — Add/remove/qty via vanilla JS
- 💳 **Pembayaran iPaymu** — Virtual Account (sandbox)
- 🧾 **Invoice & Nota** — Cetak invoice/nota per order
- 📊 **Admin Panel** — CRUD produk, kategori, layanan, brand partner, manajemen order
- 📥 **Export CSV** — Export data order ke Excel (separator `;`, BOM untuk Excel Indonesia)
- 🔐 **Auth** — Login/register, role admin & customer
- 📱 **Responsive** — Tailwind CSS, mobile-first

---

## 🛠️ Tech Stack

| Bagian   | Teknologi                                          |
| -------- | --------------------------------------------------- |
| Backend  | PHP 8.3, Laravel 13, MySQL                          |
| Frontend | Tailwind CSS, Alpine.js, Vite                       |
| Payment  | iPaymu (Virtual Account / QRIS)                     |
| Hosting  | InfinityFree (Free hosting)                         |
| Auth     | Laravel Breeze                                      |

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
# konfigurasi DB di .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik — Universitas Tirtayasa.
