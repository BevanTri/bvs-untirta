# Guidebook — BVS Bengkel Untirta
## Panduan Eksekusi & Pengerjaan Proyek

---

## 1. Alur Kerja Pengembangan

### Fase 1 — Foundation (Hari 1-2)
- [x] Setup Laravel + Breeze + Sanctum
- [x] Database migration & seeders
- [x] Auth scaffolding (admin + customer guard)

### Fase 2 — Core CRUD (Hari 3-5)
- [x] Produk, Kategori, Service, Brand
- [x] Pelanggan, Kendaraan, Mekanik
- [x] Slug generation + image upload

### Fase 3 — Transaksi (Hari 6-8)
- [x] Keranjang belanja (cart_items)
- [x] Checkout + iPaymu payment
- [x] Repair order + sparepart tracking
- [x] Payment polymorphic

### Fase 4 — Workshop & Laporan (Hari 9-11)
- [x] Repair order status lifecycle
- [x] Sparepart → sales conversion
- [x] Laporan + export CSV
- [x] Dashboard admin

### Fase 5 — Polishing (Hari 12-14)
- [x] Responsive design + dark mode
- [x] REST API (Sanctum)
- [x] 137 test cases
- [x] Deployment InfinityFree

---

## 2. Perintah Penting

```bash
# Development
php artisan serve                          # Start server lokal
npm run dev                                # Build frontend (watch)
npm run build                              # Build frontend (production)

# Database
php artisan migrate                        # Jalankan migrasi
php artisan migrate:fresh --seed           # Reset DB + seed
php artisan db:seed                        # Seed data awal

# Cache
php artisan optimize                       # Optimasi Laravel
php artisan route:cache                    # Cache route
php artisan config:cache                   # Cache config

# Storage
php artisan storage:link                   # Symlink storage
```

---

## 3. Environment Variables (.env)

```ini
APP_NAME="BVS Bengkel Untirta"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bvs_bengkel
DB_USERNAME=root
DB_PASSWORD=

# iPaymu (Sandbox)
IPAYMU_URL=https://sandbox.ipaymu.com/api/v2
IPAYMU_VIRTUAL_ACCOUNT=YOUR_VA
IPAYMU_API_KEY=YOUR_API_KEY

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost
```

---

## 4. Struktur Kunci

| Path | Fungsi |
|:---|:---|
| `routes/web.php` | Rute utama web |
| `routes/api.php` | Rute REST API |
| `routes/auth.php` | Rute autentikasi |
| `app/Http/Controllers/Admin/` | Admin controllers |
| `app/Http/Controllers/Api/` | API controllers |
| `app/Models/` | Eloquent models |
| `resources/views/` | Blade templates |
| `database/migrations/` | Database migrations |
| `database/seeders/` | Data seeders |

---

## 5. Deployment (InfinityFree)

1. Export database via phpMyAdmin → Import ke InfinityFree MySQL
2. Upload semua file via FTP ke `htdocs/`
3. Edit `.env` → set `APP_ENV=production`, `APP_DEBUG=false`, DB credentials InfinityFree
4. Set `APP_URL=https://bvsuntirta.rf.gd`
5. Clear cache: `php artisan optimize`

---

## 6. Checklist Presentasi

- [ ] Local server siap (`php artisan serve`)
- [ ] Database tersedia (local + live)
- [ ] Dark/Light mode toggle berfungsi
- [ ] Produk + kategori terisi (min 5)
- [ ] Test credentials siap (admin + customer)
- [ ] Postman collection siap demo API
- [ ] Live URL dapat diakses: https://bvsuntirta.rf.gd
