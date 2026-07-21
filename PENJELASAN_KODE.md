# Penjelasan Kode — BVS Untirta (Bengkel & Variasi Motor)

## Tech Stack
- **Framework:** Laravel 13 (PHP 8.3)
- **Database:** MySQL
- **Frontend:** Tailwind CSS, Vite, Blade
- **Payment:** iPaymu (sandbox)
- **Auth:** Laravel Breeze + Sanctum (API token)
- **Hosting:** InfinityFree

---

## 1. Routes (`routes/web.php`)

Routes adalah pintu masuk aplikasi — menentukan URL mana yang memanggil Controller mana.

### Public Routes (tanpa login)

| URL | Method | Controller::method | Fungsi |
|-----|--------|-------------------|--------|
| `/` | GET | `PageController::home` | Tampilkan halaman utama (hero, kategori, produk unggulan, brand partner) |
| `/produk` | GET | `PageController::products` | Tampilkan semua produk dengan filter kategori dan pagination |
| `/produk/kategori/{slug}` | GET | `PageController::products` | Filter produk berdasarkan kategori |
| `/produk/{slug}` | GET | `PageController::productDetail` | Detail satu produk |
| `/service` | GET | `PageController::services` | Tampilkan layanan bengkel |

### Auth Routes (wajib login)

| URL | Method | Controller | Fungsi |
|-----|--------|-----------|--------|
| `/servis` | GET | `RepairOrderController::index` | Daftar pesanan service user |
| `/servis/buat` | GET | `RepairOrderController::create` | Form buat pesanan service baru |
| `/servis` | POST | `RepairOrderController::store` | Simpan pesanan service |
| `/servis/{id}` | GET | `RepairOrderController::show` | Detail pesanan service |
| `/servis/{id}/bayar` | GET | `RepairOrderController::pay` | Halaman bayar service |
| `/cart` | GET | `CartController::index` | Tampilkan keranjang |
| `/cart/add` | POST | `CartController::add` | Tambah item ke keranjang |
| `/cart/checkout` | POST | `CartController::checkout` | Konversi keranjang ke order |
| `/checkout` | GET | `CheckoutController::index` | Form checkout |
| `/checkout` | POST | `CheckoutController::process` | Proses checkout |
| `/pesanan` | GET | `OrderController::history` | History pesanan (produk + service) |
| `/pesanan/{id}/bayar` | GET | `OrderController::pay` | Halaman bayar order produk |
| `/payment/checkout/{order}` | POST | `PaymentController::checkout` | Kirim order ke iPaymu |
| `/payment/repair/{repair}` | POST | `PaymentController::payRepair` | Kirim repair order ke iPaymu |
| `/payment/{order}/success` | GET | `PaymentController::success` | Handle redirect dari iPaymu |

### Admin Routes (`/admin/*`)

| URL | Method | Controller | Fungsi |
|-----|--------|-----------|--------|
| `/admin/dashboard` | GET | `AdminController::dashboard` | Dashboard dengan statistik + grafik 7 hari |
| `/admin/categories` | GET/POST | `AdminController` | CRUD kategori produk |
| `/admin/products` | GET/POST | `AdminController` | CRUD produk (265 produk) |
| `/admin/services` | GET/POST | `AdminController` | CRUD layanan (9 service) |
| `/admin/brands` | GET/POST | `AdminController` | CRUD brand partner |
| `/admin/orders` | GET | `AdminController::orders` | Daftar semua order + search |
| `/admin/orders/export` | GET | `AdminController::exportOrdersCsv` | Export order ke CSV |
| `/admin/customers` | GET/POST | `Admin\CustomerController` | CRUD customer bengkel |
| `/admin/vehicles` | GET/POST | `Admin\VehicleController` | CRUD kendaraan |
| `/admin/mechanics` | GET/POST | `Admin\MechanicController` | CRUD mekanik |
| `/admin/repair-orders` | GET/POST | `Admin\RepairOrderController` | CRUD repair order |
| `/admin/reports` | GET | `Admin\ReportController::index` | Laporan revenue periodik |

### Utility Routes

| URL | Fungsi |
|-----|--------|
| `/setup` | Jalanin migration + seed via web (karena InfinityFree gak ada SSH) |
| `/fix-session` | Fix session driver + alter table payments |
| `/reseed` | Ulang semua seeders |
| `/admin/reset-workshop` | Hapus semua data workshop (customer, vehicle, repair order) |
| `/debug-ipaymu` | Debug iPaymu signature & response |
| `/sync-products-images` | Sync nama file gambar produk ke database |

### API Routes (`routes/api.php`)

| URL | Method | Auth | Fungsi |
|-----|--------|------|--------|
| `/api/token` | POST | - | Login via email+password → return Sanctum token |
| `/api/services` | GET | Sanctum | Ambil daftar service (JSON) |
| `/api/customers` | GET | Sanctum | Ambil daftar customer (JSON) |

---

## 2. Controllers (`app/Http/Controllers/`)

### Public Controllers

| Controller | Method | Fungsi |
|-----------|--------|--------|
| **PageController** | `home()` | Tampilkan halaman utama: categories, products, services, brand partners |
| | `products()` | Tampilkan semua produk dengan pagination + filter kategori |
| | `productDetail()` | Detail produk by slug |
| | `services()` | Tampilkan semua service |
| **CartController** | `index()` | Tampilkan isi keranjang user |
| | `add()` | Tambah produk ke keranjang (cek duplikat + increment qty) |
| | `update()` | Update quantity item keranjang |
| | `destroy()` | Hapus item dari keranjang |
| | `checkout()` | Konversi item keranjang yang dicentang ke Order |
| **CheckoutController** | `index()` | Tampilkan form checkout |
| | `process()` | Validasi + buat Order, redirect ke payment |
| **OrderController** | `history()` | Tampilkan history order (produk + service) |
| | `show()` | Detail order |
| | `pay()` | Halaman bayar order |
| | `checkPayment()` | Cek status pembayaran manual |
| **PaymentController** | `checkout()` | Buat transaksi iPaymu untuk Order |
| | `payRepair()` | Buat transaksi iPaymu untuk RepairOrder |
| | `callback()` | Handle callback dari iPaymu |
| | `success()` | Handle redirect dari iPaymu (auto-update status) |
| **RepairOrderController** | `index()` | Daftar repair order milik user |
| | `create()` | Form buat repair order baru |
| | `store()` | Simpan repair order (auto-create Customer + Vehicle jika baru) |
| | `show()` | Detail repair order |
| | `pay()` | Halaman bayar |
| | `checkPayment()` | Cek status pembayaran |
| | `invoice()` | Cetak invoice |

### Admin Controllers

| Controller | Method | Fungsi |
|-----------|--------|--------|
| **AdminController** | `dashboard()` | Statistik (total produk, order, revenue) + grafik 7 hari |
| | `categories()` | List + CRUD kategori |
| | `products()` | List + CRUD produk (inline edit) |
| | `services()` | List + CRUD service |
| | `brands()` | List + CRUD brand partner |
| | `orders()` | List order + search |
| | `exportOrdersCsv()` | Export CSV dengan BOM (UTF-8 for Excel) |
| | `invoice()` | Cetak invoice order |
| | `runArtisan()` | Jalanin Artisan command via web |
| **Admin\CustomerController** | CRUD customer (20+ customer) |
| **Admin\VehicleController** | CRUD kendaraan per customer |
| **Admin\MechanicController** | CRUD mekanik (5 mekanik) |
| **Admin\RepairOrderController** | CRUD repair order + search + filter status |
| **Admin\ReportController** | `index()` | Laporan revenue (daily/weekly/monthly/yearly) |

---

## 3. Models (`app/Models/`) — 14 Model

### E-commerce Models

```
Category ——— hasMany ——— Product
                              │
                              │ morphMany('itemable')
                              v
                          OrderItem
                              │
                              │ belongsTo
                              v
                          Order ——— belongsTo ——— User
                              │
                              │ morphMany('payable')
                              v
                          Payment

User ——— hasMany ——— CartItem ——— morphTo('itemable')
                                            │
                                     bisa Product atau Service

BrandPartner (standalone, untuk ditampilkan di homepage)
```

### Workshop Models

```
Customer ——— hasMany ——— Vehicle
     │                       │
     │                       │ hasMany
     │                       v
     └—————— hasMany ——— RepairOrder
                              │
                              │ belongsTo
                              v
                          Mechanic
                              │
                              │ hasMany
                              v
                          RepairOrder
                              │
                              │ hasMany
                              v
                     RepairOrderItem ——— belongsTo ——— Product
                              │
                              │ morphMany('payable')
                              v
                          Payment
```

### Model Details

| Model | Table | Kolom Penting | Relasi |
|-------|-------|---------------|--------|
| **User** | users | name, email, password, **is_admin** (boolean) | hasMany(Order), hasMany(CartItem) |
| **Category** | categories | name, slug, description, icon, is_active | hasMany(Product) |
| **Product** | products | category_id, name, slug, price (decimal 12,2), stock, image | belongsTo(Category), morphMany(OrderItem) |
| **Service** | services | name, slug, price, description | morphMany(OrderItem) |
| **CartItem** | cart_items | user_id, itemable_id/type, qty, unit_price | belongsTo(User), morphTo('itemable') |
| **Order** | orders | user_id, order_number, status, payment_status, subtotal, total | hasMany(OrderItem), morphMany(Payment) |
| **OrderItem** | order_items | order_id, itemable_id/type, name, qty, price | morphTo('itemable') |
| **Payment** | payments | payable_id/type (polymorphic), method, amount, status, reference_id, payment_url, raw_response | morphTo('payable') |
| **Customer** | customers | name, address, email | hasMany(Vehicle), hasMany(RepairOrder) |
| **Vehicle** | vehicles | customer_id, plate_number, brand, model, year, color | belongsTo(Customer), hasMany(RepairOrder) |
| **Mechanic** | mechanics | name, specialist | hasMany(RepairOrder) |
| **RepairOrder** | repair_orders | customer_id, vehicle_id, mechanic_id, user_id, order_number, complaint, action, service_fee, total, status, payment_status | belongsTo(Customer/Vehicle/Mechanic/User), hasMany(RepairOrderItem), morphMany(Payment) |
| **RepairOrderItem** | repair_order_items | repair_order_id, product_id, name, qty, price | belongsTo(RepairOrder), belongsTo(Product) |

### Polymorphic Relationships

1. **OrderItem.itemable** — Satu tabel `order_items` bisa nampung `Product` ATAU `Service`. Ini memungkinkan satu order berisi campuran produk dan service.
2. **Payment.payable** — Satu tabel `payments` bisa nampung `Order` ATAU `RepairOrder`. Jadi histori pembayaran e-commerce dan bengkel ada di satu tabel.

---

## 4. Views (`resources/views/`)

### Layouts

| File | Digunakan Untuk | Struktur |
|------|----------------|---------|
| `layouts/public.blade.php` | Halaman publik (home, produk, service) | Navbar atas → content → footer → bottom nav mobile |
| `layouts/app.blade.php` | Halaman user login (cart, pesanan, profile) | Navigation + content + footer + bottom nav |
| `layouts/admin.blade.php` | Halaman admin | Sidebar (desktop) / hamburger (mobile) + content |
| `layouts/guest.blade.php` | Login & register | Minimalis, sentral, background navy |
| `layouts/navigation.blade.php` | Navbar untuk user login | Link cart, pesanan, logout |
| `layouts/partials/bottom-nav.blade.php` | Navigasi bawah mobile | Beranda, Produk, Service, Admin (jika admin) |

### Halaman Publik

| File | Isi |
|------|-----|
| `pages/home.blade.php` | Hero section, kategori produk, produk unggulan, services, brand partner, stats counter |
| `pages/products.blade.php` | Sidebar kategori, grid produk, pagination |
| `pages/product-detail.blade.php` | Gambar, nama, harga, stock, deskripsi, input quantity + tombol tambah ke keranjang |
| `pages/services.blade.php` | Grid layanan dengan harga + tombol "Pesan" |

### Halaman Order & Cart

| File | Isi |
|------|-----|
| `cart/index.blade.php` | Tabel item keranjang, checkbox, update qty, total, tombol checkout |
| `checkout/index.blade.php` | Form data customer, ringkasan order, tombol bayar |
| `orders/history.blade.php` | List semua order (produk + repair) dengan status |
| `orders/pay.blade.php` | Halaman bayar dengan tombol "Bayar via iPaymu" + "Cek Pembayaran" |
| `orders/show.blade.php` | Detail order + daftar item |
| `orders/success.blade.php` | Halaman sukses setelah bayar |

### Halaman Service (Repair)

| File | Isi |
|------|-----|
| `repairs/index.blade.php` | Daftar repair order milik user |
| `repairs/create.blade.php` | Form: pilih kendaraan (lama/baru), service, sparepart, komplain |
| `repairs/show.blade.php` | Detail repair order |
| `repairs/pay.blade.php` | Halaman bayar repair order |
| `repairs/invoice.blade.php` | Invoice printable |

### Halaman Admin

| File | Isi |
|------|-----|
| `admin/dashboard.blade.php` | Stat cards (produk, order, revenue, customer) + grafik batang 7 hari |
| `admin/categories.blade.php` | Tabel + form CRUD kategori (inline edit) |
| `admin/products.blade.php` | Tabel + form CRUD produk (265 produk, inline edit + image) |
| `admin/services.blade.php` | Tabel + form CRUD service |
| `admin/brands.blade.php` | Tabel + form CRUD brand partner |
| `admin/orders.blade.php` | Tabel order + search + dropdown status |
| `admin/customers.blade.php` | Tabel CRUD customer |
| `admin/vehicles.blade.php` | Tabel CRUD kendaraan |
| `admin/mechanics.blade.php` | Tabel CRUD mekanik |
| `admin/repair-orders.blade.php` | Tabel repair order + filter status |
| `admin/repair-orders-form.blade.php` | Form create/edit repair order |
| `admin/repair-orders-show.blade.php` | Detail repair order |
| `admin/reports.blade.php` | Filter periode + tabel revenue |

### Auth Views (Laravel Breeze)

`auth/login.blade.php`, `auth/register.blade.php`, `auth/forgot-password.blade.php`, `auth/reset-password.blade.php`, `auth/verify-email.blade.php`

---

## 5. Database — Tabel & Migrasi

### E-commerce Tables

| Tabel | Kolom Penting |
|-------|---------------|
| **users** | id, name, email, password, is_admin (boolean) |
| **categories** | id, name, slug, description, icon, is_active |
| **products** | id, category_id (FK), name, slug, price (decimal 12,2), stock (int), image |
| **services** | id, name, slug, price, description, is_active |
| **cart_items** | id, user_id (FK), itemable_id, itemable_type, quantity, unit_price |
| **orders** | id, user_id (FK), order_number (unique), status (enum), payment_status, total |
| **order_items** | id, order_id (FK), itemable_id, itemable_type, name, qty, price |
| **payments** | id, payable_id, payable_type (polymorphic), amount, status, reference_id, payment_url |
| **brand_partners** | id, name, logo, url, is_active |

### Workshop Tables

| Tabel | Kolom Penting |
|-------|---------------|
| **customers** | id, name, address, email |
| **vehicles** | id, customer_id (FK), plate_number, brand, model, year, color |
| **mechanics** | id, name, specialist |
| **repair_orders** | id, customer_id (FK), vehicle_id (FK), mechanic_id (FK), user_id (FK), order_number, complaint, action, service_fee, total, status (enum), payment_status |
| **repair_order_items** | id, repair_order_id (FK), product_id (FK), name, qty, price |

### System Tables

| Tabel | Fungsi |
|-------|--------|
| **sessions** | Session storage (pake database karena file session gak reliable di InfinityFree) |
| **personal_access_tokens** | Token API Sanctum |
| **cache, cache_locks** | Laravel cache |
| **password_reset_tokens** | Reset password |

---

## 6. Database Seeders

| Seeder | Data |
|--------|------|
| **DatabaseSeeder** | 2 user: admin@bengkel.test / customer@bengkel.test |
| **CategorySeeder** | 4 kategori: Ban, Aki, Shock Absorber, Oli |
| **BrandPartnerSeeder** | 11 brand: Pirelli, Castrol, Yamalube, Motul, dll |
| **ServiceSeeder** | 6 service: Service Mesin, Ganti Oli, Tune Up, dll |
| **ProductSeeder** | ~265 produk (ban, aki, shock, oli) |
| **CustomerSeeder** | 20 customer |
| **VehicleSeeder** | 30+ kendaraan (1-3 per customer) |
| **MechanicSeeder** | 5 mekanik (Mesin, Kelistrikan, Sasis, AC, Umum) |
| **RepairOrderSeeder** | 50 repair order |

---

## 7. Fitur & Alur Bisnis

### Alur Pembelian Produk
```
Browse Produk → Add to Cart → Checkout → Order Created → Bayar via iPaymu → Redirect → Status Auto-Update
```

### Alur Service Bengkel
```
Buat Repair Order (pilih kendaraan + service + sparepart) → Admin proses → Service selesai → Bayar via iPaymu
```

### Alur Admin
```
Dashboard (statistik) → CRUD Produk/Kategori/Service/Brand → Manage Order → Export CSV → Laporan Revenue
```

---

## 8. Hal Teknis yang Perlu Disorot

### 8.1 Polymorphic Relationships
- `order_items.itemable` — Satu tabel bisa nampung Product ATAU Service
- `payments.payable` — Satu tabel bisa nampung Order ATAU RepairOrder
- Ini membuat struktur database lebih fleksibel tanpa perlu tabel pembayaran terpisah

### 8.2 iPaymu Payment Gateway
- Tidak menggunakan library official (karena ada bug signature)
- Implementasi manual: raw cURL + HMAC-SHA256 signature
- Endpoint: `https://sandbox.ipaymu.com/api/v2/payment`
- Signature: `hash_hmac('sha256', "POST:{va}:{sha256(json_body)}:{apiKey}", apiKey)`
- InfinityFree Bot Protection memblock callback → solusi: auto-update status saat user redirect balik ke halaman sukses

### 8.3 Sanctum API
- `POST /api/token` — Login via email + password, return plain text token
- Endpoint terproteksi: `GET /api/services`, `GET /api/customers`
- Bisa diakses dari mobile app atau Postman

### 8.4 Deployment Strategy (InfinityFree)
- **Problem:** InfinityFree tidak menyediakan SSH / Artisan CLI
- **Solusi:** Membuat route `/setup` yang bisa menjalankan `migrate:fresh` dan `db:seed` via web
- **Session:** InfinityFree tidak reliable untuk file session → pake database session driver
- **APP_URL:** Harus diset sesuai domain agar `asset()` menghasilkan URL yang benar

### 8.5 Frontend
- **Tailwind CSS** dengan custom design system: navy `#0F172A` + gold `#F59E0B` = identitas Untirta
- **Mobile-first responsive:** Semua halaman bisa diakses dari HP
- **CSS Hash:** Setiap build menghasilkan hash unik (`app-DhU0CEjf.css`). Layout file harus reference hash yang benar.

### 8.6 Export CSV
- Menggunakan delimiter `;` (bukan koma) untuk kompatibilitas Excel Indonesia
- Menambahkan BOM (Byte Order Mark) UTF-8 agar file terbaca benar di Excel

---

## 9. Struktur Direktori

```
bengkel-motor/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Admin controllers
│   │   │   ├── Api/          # API controllers
│   │   │   ├── Auth/         # Auth controllers (Breeze)
│   │   │   ├── PageController.php
│   │   │   ├── CartController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ...
│   │   └── Resources/        # API Resources (JSON transform)
│   ├── Models/               # 14 model
│   └── Services/             # IpaymuService
├── bootstrap/
│   └── app.php               # App configuration
├── config/                   # App config files
├── database/
│   ├── migrations/           # 20 migration files
│   └── seeders/              # 9 seeders
├── lang/                     # Translation files
│   ├── en/                   # English translations
│   └── id/                   # Indonesian translations
├── public/
│   ├── build/assets/         # Built CSS + JS (Vite)
│   ├── images/               # Logo
│   └── uploads/products/     # Gambar produk
├── resources/
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   ├── api.php               # API routes
│   └── auth.php              # Auth routes
├── .env                      # Environment config
├── .env.production           # Production environment
└── README.md
```

---

## 10. Cara Menjalankan

### Development
```bash
composer install
npm install
cp .env.example .env   # Isi database config
php artisan key:generate
php artisan migrate --seed
npm run dev            # atau: npm run build
php artisan serve
```

### Production (InfinityFree)
1. Upload semua file ke folder `htdocs/`
2. Copy `.env.production` → `.env`
3. Akses `http://domain.com/setup` untuk migrasi & seed
4. Setting `APP_URL` di `.env` sesuai domain

### Login
- **Admin:** admin@bengkel.test / password
- **Customer:** customer@bengkel.test / password
