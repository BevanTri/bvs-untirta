# Entity Relationship Diagram (ERD) — BVS Bengkel Untirta

**Database:** MySQL  
**Tech:** Laravel 13, Eloquent ORM

---

## Daftar Tabel Bisnis (14 tabel)

| No | Tabel | Keterangan |
|:--:|:---|:---|
| 1 | `users` | Pengguna sistem (admin) |
| 2 | `customers` | Data pelanggan bengkel |
| 3 | `vehicles` | Data kendaraan pelanggan |
| 4 | `mechanics` | Data mekanik bengkel |
| 5 | `categories` | Kategori produk / sparepart |
| 6 | `products` | Data produk / sparepart |
| 7 | `services` | Daftar jasa servis bengkel |
| 8 | `brand_partners` | Mitra brand (display publik) |
| 9 | `orders` | Transaksi penjualan produk |
| 10 | `order_items` | Item dalam transaksi penjualan |
| 11 | `repair_orders` | Transaksi servis kendaraan |
| 12 | `repair_order_items` | Sparepart yang digunakan dalam servis |
| 13 | `payments` | Riwayat pembayaran (polymorphic) |
| 14 | `cart_items` | Keranjang belanja sementara |

---

## Relasi Antar Tabel

```
users
  │
  ├──1:N──► orders
  │            │
  │            └──1:N──► order_items ◄──N:1── products
  │
  └──1:N──► repair_orders
               │
               ├──1:N──► repair_order_items ◄──N:1── products
               │
               └──1:N──► payments (polymorphic)
                            ▲
                            │ (also payable by)
                            │
                         orders

customers
  │
  ├──1:N──► vehicles ──1:N──► repair_orders
  │
  ├──1:N──► cart_items
  │
  └──1:N──► orders

products ──N:1──► categories
products ──N:1──► brand_partners

payments (polymorphic: payable_id + payable_type)
  ├── payable_type = 'App\\Models\\Order' → orders
  └── payable_type = 'App\\Models\\RepairOrder' → repair_orders
```

---

## Kamus Data

### `users`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| name | varchar(255) | Not Null |
| email | varchar(255) | Unique, Not Null |
| password | varchar(255) | Not Null (Bcrypt) |
| is_admin | boolean | Default false |
| timestamps | — | created_at, updated_at |

### `customers`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| name | varchar(255) | Not Null |
| phone | varchar(30) | Not Null |
| address | text | Nullable |
| email | varchar(255) | Unique |
| password | varchar(255) | Not Null (Bcrypt) |
| timestamps | — | created_at, updated_at |

### `vehicles`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| customer_id | bigint | FK → customers.id |
| plate_number | varchar(20) | Unique, Not Null |
| brand | varchar(100) | Not Null |
| model | varchar(100) | Not Null |
| timestamps | — | created_at, updated_at |

### `mechanics`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| name | varchar(255) | Not Null |
| phone | varchar(30) | Nullable |
| specialist | varchar(150) | Nullable |
| timestamps | — | created_at, updated_at |

### `products`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| category_id | bigint | FK → categories.id |
| brand_id | bigint | FK → brand_partners.id, Nullable |
| name | varchar(255) | Not Null |
| slug | varchar(255) | Unique |
| price | decimal(12,2) | Not Null |
| stock | integer | Not Null, Default 0 |
| image | varchar(255) | Nullable |
| is_active | boolean | Default true |
| timestamps | — | created_at, updated_at |

### `repair_orders`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| user_id | bigint | FK → users.id (admin pencatat) |
| vehicle_id | bigint | FK → vehicles.id |
| mechanic_id | bigint | FK → mechanics.id, Nullable |
| service_id | bigint | FK → services.id, Nullable |
| order_number | varchar(20) | Unique |
| date | date | Not Null |
| complaint | text | Not Null |
| total | decimal(12,2) | Default 0 |
| status | enum | menunggu, proses, selesai, dibatalkan |
| payment_status | enum | unpaid, paid, failed, expired |
| timestamps | — | created_at, updated_at |

### `orders`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| user_id | bigint | FK → users.id |
| customer_id | bigint | FK → customers.id, Nullable |
| order_number | varchar(20) | Unique |
| total_price | decimal(12,2) | Not Null |
| status | enum | pending, processing, completed, cancelled |
| payment_status | enum | unpaid, paid, failed, expired |
| timestamps | — | created_at, updated_at |

### `payments`
| Kolom | Tipe | Constraint |
|:---|:---|:---|
| id | bigint | PK, Auto Increment |
| payable_id | bigint | Not Null (polymorphic) |
| payable_type | varchar(255) | Not Null (polymorphic) |
| amount | decimal(12,2) | Not Null |
| method | varchar(50) | iPaymu, manual |
| status | enum | pending, paid, failed, expired |
| reference | varchar(255) | Nullable (iPaymu ref) |
| timestamps | — | created_at, updated_at |
