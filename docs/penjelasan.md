# Penjelasan Teknis — BVS Bengkel Untirta
## Sistem Informasi Bengkel Terpadu — Panduan Presentasi

> **Mata Kuliah:** Pemrograman Web + Sistem Basis Data
> **Tim:** 2 Orang
> **Stack:** Laravel 13 · MySQL · Tailwind CSS · Laravel Sanctum · iPaymu

---

## 1. Arsitektur MVC

```
app/
├── Http/
│   ├── Controllers/     ← 34 file (Web, Admin, API)
│   └── Middleware/      ← Role check middleware
├── Models/              ← 13 Eloquent models
├── Services/            ← Business logic services
└── Notifications/       ← In-app notification engine

database/
├── migrations/          ← 26 file migrasi terstruktur
└── seeders/             ← Data awal

resources/views/         ← Blade templates (admin, customer, public, layouts)
routes/                  ← web.php, api.php, auth.php
```

### Eloquent ORM — Zero Raw SQL

Semua query menggunakan Eloquent ORM (PDO prepared statements). Contoh eager loading:

```php
// RepairOrderController
$repairOrders = RepairOrder::with(['vehicle.customer', 'mechanic', 'items.product'])
    ->orderBy('created_at', 'desc')->paginate(15);
```

### Validasi Input

```php
$validated = $request->validate([
    'vehicle_id'  => 'required|exists:vehicles,id',
    'mechanic_id' => 'nullable|exists:mechanics,id',
    'complaint'   => 'required|string',
    'total'       => 'required|numeric|min:0',
    'status'      => 'required|in:menunggu,proses,selesai,dibatalkan',
]);
```

---

## 2. Fitur Unggulan

### E-Commerce Sparepart
- Katalog 265+ produk, filter kategori & brand
- Keranjang belanja persisten (cart_items table)
- Checkout iPaymu (Virtual Account sandbox)
- Invoice compact 420px

### Workshop Management
- Repair order lifecycle: menunggu → proses → selesai/dibatalkan
- Sparepart tracking otomatis stok berkurang
- Konversi sparepart ke transaksi penjualan saat selesai
- Payment polymorphic (satu payment table untuk order + repair order)

### Pembayaran Terpadu
- iPaymu callback → update status otomatis
- Konfirmasi manual oleh admin
- Stok rollback jika payment failed/expired

---

## 3. REST API (Sanctum)

| Endpoint | Method | Auth | Fungsi |
|:---|:---:|:---:|:---|
| `/api/token` | POST | Public | Dapatkan Bearer Token |
| `/api/logout` | POST | Bearer | Revoke token |
| `/api/services` | GET | Bearer | Riwayat repair order |
| `/api/customers` | GET | Bearer | Daftar pelanggan |

**Contoh cURL:**
```bash
# Step 1: Login
curl -X POST /api/token -H "Content-Type: application/json" \
  -d '{"email":"admin@bengkel.test","password":"password","device_name":"Postman"}'

# Step 2: Akses API
curl /api/services -H "Authorization: Bearer {token}"
```

---

## 4. Keamanan

| Mekanisme | Implementasi |
|:---|:---|
| CSRF | `@csrf` di setiap form |
| SQL Injection | Eloquent ORM + PDO prepared statements |
| XSS | Blade `{{ }}` auto-escape |
| Password | Bcrypt hashing |
| Role Middleware | Custom CheckRole |
| API Auth | Laravel Sanctum Bearer Token |
| Mass Assignment | `$fillable` eksplisit |

---

## 5. Tips Demo

1. Buka landing page → toggle dark mode
2. Login admin → dashboard dengan chart
3. CRUD: tambah produk, pelanggan, kendaraan
4. Buat repair order → update status → sparepart tracking
5. Login customer → add to cart → checkout iPaymu
6. API via Postman: login → GET /api/services

---

## Ringkasan

- **265+** produk sparepart
- **14** tabel database bisnis
- **137** test cases blackbox
- **19** endpoint REST API
- **Dual auth**: Admin (Breeze) + Customer
- **Payment**: iPaymu Virtual Account
- **Deploy**: InfinityFree live
