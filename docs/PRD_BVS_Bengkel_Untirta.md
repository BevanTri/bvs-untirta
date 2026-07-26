# Product Requirements Document (PRD)
# Single Source of Truth (SSOT) — BVS Bengkel Untirta

| Atribut Dokumen | Keterangan & Spesifikasi |
| :--- | :--- |
| **Nama Proyek** | BVS Bengkel Untirta (Bengkel Virtual Shop) |
| **Mata Kuliah** | Pemrograman Web + Sistem Basis Data |
| **Jenis Dokumen** | **Single Source of Truth (SSOT) Product Requirements Document** |
| **Versi Dokumen** | 1.0 (Final SSOT — Consolidated) |
| **Status Dokumen** | **Disetujui & Siap Diimplementasikan** |
| **Tanggal Terbit** | Juli 2026 |
| **Tim Proyek** | 2 Orang |

---

## 1. Ringkasan Eksekutif

**BVS Bengkel Untirta** adalah platform web terintegrasi yang mendigitalisasi operasional bengkel otomotif dan menyediakan e-commerce sparepart. Sistem mencakup katalog produk (265+ item), manajemen servis kendaraan, pembayaran online via iPaymu, serta REST API untuk integrasi eksternal.

Dibangun menggunakan **Laravel 13** (MVC), **MySQL**, **Tailwind CSS**, dan **Laravel Sanctum**.

---

## 2. Stakeholder & User Personas

### A. Admin Bengkel
- **Tujuan**: Mengelola katalog produk, memproses pesanan, memantau repair order, melihat laporan.
- **Kebutuhan**: Dashboard, CRUD semua entitas, export CSV, manajemen pembayaran.

### B. Pelanggan
- **Tujuan**: Membeli sparepart, booking servis, cek status kendaraan.
- **Kebutuhan**: Katalog produk, keranjang belanja, checkout iPaymu, riwayat pesanan.

---

## 3. Ruang Lingkup Sistem

### In-Scope
- Autentikasi (Admin Breeze + Customer)
- E-Commerce (katalog, cart, checkout iPaymu, invoice)
- Workshop Management (repair order, sparepart tracking, mekanik)
- CRUD: Produk, Kategori, Service, Brand, Pelanggan, Kendaraan, Mekanik
- REST API (Sanctum)
- Laporan & Export CSV
- Dark Mode

### Out-of-Scope
- Aplikasi Mobile Native
- Notifikasi WhatsApp/SMS otomatis
- Multi-cabang bengkel

---

## 4. Arsitektur Data

### ERD

```
users ──1:N──► orders ──1:N──► order_items ◄──N:1── products
                                             ▲
                                             │ (polymorphic)
customers ──1:N──► vehicles ──1:N──► repair_orders ──1:N──► repair_order_items ◄──N:1── products
                                             │
                                        payments (polymorphic: order / repair_order)

customers ──1:N──► cart_items
products ──N:1──► categories
products ──N:1──► brand_partners
```

### Data Dictionary

**Tabel Bisnis (14 tabel):**

| No | Tabel | Keterangan |
|:--:|:---|:---|
| 1 | `users` | Pengguna sistem (admin) |
| 2 | `customers` | Data pelanggan bengkel |
| 3 | `vehicles` | Data kendaraan pelanggan |
| 4 | `mechanics` | Data mekanik bengkel |
| 5 | `categories` | Kategori produk / sparepart |
| 6 | `products` | Data produk / sparepart |
| 7 | `services` | Daftar jasa servis bengkel |
| 8 | `brand_partners` | Mitra brand (display halaman publik) |
| 9 | `orders` | Transaksi penjualan produk |
| 10 | `order_items` | Item dalam transaksi penjualan |
| 11 | `repair_orders` | Transaksi servis kendaraan |
| 12 | `repair_order_items` | Sparepart yang digunakan dalam servis |
| 13 | `payments` | Riwayat pembayaran (polymorphic) |
| 14 | `cart_items` | Keranjang belanja sementara |

---

## 5. RBAC & Hak Akses

| Modul | Admin | Customer (via Portal) |
|:---|:---:|:---:|
| Dashboard | ✅ | ❌ |
| Produk (CRUD) | ✅ | ❌ |
| Kategori (CRUD) | ✅ | ❌ |
| Service (CRUD) | ✅ | ❌ |
| Brand Partner (CRUD) | ✅ | ❌ |
| Pelanggan (CRUD) | ✅ | ❌ |
| Kendaraan (CRUD) | ✅ | ❌ |
| Mekanik (CRUD) | ✅ | ❌ |
| Order (manage) | ✅ | View only |
| Repair Order (manage) | ✅ | View only |
| Payment (manage) | ✅ | ❌ |
| Laporan & Export CSV | ✅ | ❌ |
| Katalog Produk | ✅ | ✅ |
| Keranjang & Checkout | ❌ | ✅ |
| Cek Status Servis | ✅ | ✅ (publik) |

---

## 6. Rute Web (UI/UX Frontend)

### Public Routes
| URL | Method | Controller | Fungsi |
|:---|:---:|:---|:---|
| `/` | GET | `PageController@home` | Landing page |
| `/produk` | GET | `PageController@products` | Katalog produk |
| `/produk/kategori/{slug}` | GET | `PageController@products` | Filter kategori |
| `/produk/{slug}` | GET | `PageController@productDetail` | Detail produk |
| `/service` | GET | `PageController@services` | Layanan servis |
| `/cek-status` | GET | `PageController@cekStatus` | Cek status servis publik |

### Auth Routes (Customer)
| URL | Method | Controller | Fungsi |
|:---|:---:|:---|:---|
| `/cart` | GET/POST | `CartController` | Keranjang belanja |
| `/checkout` | GET/POST | `CheckoutController` | Checkout & bayar |
| `/orders` | GET | `OrderController` | Riwayat pesanan |
| `/repairs` | GET | `RepairOrderController` | Riwayat servis |
| `/repairs/create` | GET/POST | `RepairOrderController@store` | Booking servis |

### Admin Routes
| URL | Method | Controller |
|:---|:---:|:---|
| `/admin/dashboard` | GET | `AdminController@dashboard` |
| `/admin/products` | CRUD | `AdminController@product*` |
| `/admin/categories` | CRUD | `AdminController@category*` |
| `/admin/services` | CRUD | `AdminController@service*` |
| `/admin/brands` | CRUD | `AdminController@brand*` |
| `/admin/customers` | CRUD | `AdminController@customer*` |
| `/admin/vehicles` | CRUD | `AdminController@vehicle*` |
| `/admin/mechanics` | CRUD | `AdminController@mechanic*` |
| `/admin/orders` | Manage | `AdminController@orders` |
| `/admin/repair-orders` | Manage | `RepairOrderController` |
| `/admin/reports` | View | `ReportController` |

---

## 7. REST API (Sanctum)

| No | Method | Endpoint | Auth | Deskripsi |
|:--:|:---:|:---|:---:|:---|
| 1 | `POST` | `/api/token` | Public | Login & dapatkan token |
| 2 | `POST` | `/api/logout` | Bearer | Revoke token |
| 3 | `GET` | `/api/services` | Bearer | Riwayat repair order |
| 4 | `GET` | `/api/customers` | Bearer | Daftar pelanggan |

### Response Format

**Sukses:**
```json
{
  "success": true,
  "message": "Berhasil mengambil data",
  "data": [...]
}
```

**Error (401):**
```json
{
  "message": "Unauthenticated."
}
```

---

## 8. Tech Stack

| Layer | Teknologi |
|:---|:---|
| Backend | PHP 8.3, Laravel 13, MySQL |
| Frontend | Tailwind CSS 3.x, Vanilla JS, Vite |
| Payment | iPaymu (cURL, sandbox) |
| Auth | Laravel Breeze + Sanctum |
| Hosting | InfinityFree (PHP 8.3, MySQL) |

---

## 9. Test Plan

| No | Skenario | Modul |
|:--:|:---|:---|
| 1 | Login/logout valid & invalid | Auth |
| 2 | CRUD semua entitas | Master Data |
| 3 | Tambah ke cart & checkout | E-Commerce |
| 4 | Buat repair order & update status | Workshop |
| 5 | Pembayaran iPaymu callback | Payment |
| 6 | API dengan/ tanpa token | API |
| 7 | Export CSV | Laporan |
| 8 | Dark mode toggle | UI/UX |
| 9 | Responsive mobile | UI/UX |

Total: **137 test cases** — lihat [blackbox.md](blackbox.md)

---

## 10. Tim Pengembang

| Anggota | NPM | Peran |
|:---|:---:|:---|
| Bevan Tri Ramadiyas | 3337250063 | Fullstack Developer |
| Fauzi Nur Ibrahim | 3337250019 | Backend & Database Engineer |
