# SQL Queries — BVS Untirta

## 1. User Pernah Servis — dengan Mekanik, Biaya, & Plat Kendaraan

### Detail per servis
```sql
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    ro.order_number AS no_servis,
    ro.date AS tanggal,
    m.name AS mekanik,
    m.specialist AS spesialis_mekanik,
    CONCAT(v.brand, ' ', v.model) AS kendaraan,
    v.plate_number AS plat_nomor,
    ro.complaint AS komplain,
    ro.total AS total_biaya,
    ro.status,
    ro.payment_status
FROM repair_orders ro
JOIN users u ON u.id = ro.user_id
LEFT JOIN mechanics m ON m.id = ro.mechanic_id
JOIN vehicles v ON v.id = ro.vehicle_id
ORDER BY u.id, ro.date;
```

### Rekap per user (total servis, total bayar, mekanik, plat)
```sql
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    COUNT(ro.id) AS total_servis,
    COALESCE(SUM(ro.total), 0) AS total_pengeluaran_servis,
    GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ') AS mekanik_yang_menangani,
    GROUP_CONCAT(DISTINCT v.plate_number SEPARATOR ', ') AS plat_kendaraan
FROM users u
LEFT JOIN repair_orders ro ON ro.user_id = u.id
LEFT JOIN mechanics m ON m.id = ro.mechanic_id
LEFT JOIN vehicles v ON v.id = ro.vehicle_id
GROUP BY u.id, u.name
ORDER BY u.id;
```

---

## 2. User Pernah Beli Produk Apa Saja

### Detail per item pembelian
```sql
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    o.order_number AS no_order,
    o.created_at AS tanggal_beli,
    oi.name AS nama_produk,
    oi.quantity AS jumlah,
    oi.price AS harga_satuan,
    oi.subtotal AS subtotal,
    o.total AS total_order,
    o.payment_status
FROM users u
JOIN orders o ON o.user_id = u.id
JOIN order_items oi ON oi.order_id = o.id
WHERE oi.itemable_type = 'App\\Models\\Product'
ORDER BY u.id, o.created_at;
```

### Rekap per user (total order, total item, total pengeluaran)
```sql
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    COUNT(DISTINCT o.id) AS total_order,
    COALESCE(SUM(oi.quantity), 0) AS total_item_dibeli,
    COALESCE(SUM(o.total), 0) AS total_pengeluaran_belanja
FROM users u
LEFT JOIN orders o ON o.user_id = u.id
LEFT JOIN order_items oi ON oi.order_id = o.id
GROUP BY u.id, u.name
ORDER BY u.id;
```

---

## 3. Gabungan — Riwayat Servis & Belanja dalam Satu View

```sql
-- Riwayat Servis
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    'Servis' AS tipe,
    ro.order_number AS no_ref,
    ro.date AS tanggal,
    CONCAT(v.brand, ' ', v.model, ' (', v.plate_number, ')') AS keterangan,
    m.name AS mekanik,
    ro.total AS biaya,
    ro.status
FROM users u
JOIN repair_orders ro ON ro.user_id = u.id
LEFT JOIN vehicles v ON v.id = ro.vehicle_id
LEFT JOIN mechanics m ON m.id = ro.mechanic_id

UNION ALL

-- Riwayat Belanja
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    'Belanja' AS tipe,
    o.order_number AS no_ref,
    o.created_at AS tanggal,
    oi.name AS keterangan,
    '-' AS mekanik,
    oi.subtotal AS biaya,
    o.status
FROM users u
JOIN orders o ON o.user_id = u.id
JOIN order_items oi ON oi.order_id = o.id

ORDER BY user_id, tanggal;
```

---

## 4. Bonus — Dashboard Ringkasan per User

```sql
SELECT 
    u.id AS user_id,
    u.name AS nama_user,
    u.email,
    COALESCE(s.total_servis, 0) AS total_servis,
    COALESCE(s.total_pengeluaran_servis, 0) AS total_biaya_servis,
    COALESCE(b.total_order, 0) AS total_belanja,
    COALESCE(b.total_pengeluaran_belanja, 0) AS total_biaya_belanja,
    COALESCE(s.total_pengeluaran_servis, 0) + COALESCE(b.total_pengeluaran_belanja, 0) AS total_keseluruhan
FROM users u
LEFT JOIN (
    SELECT user_id, COUNT(*) AS total_servis, SUM(total) AS total_pengeluaran_servis
    FROM repair_orders
    GROUP BY user_id
) s ON s.user_id = u.id
LEFT JOIN (
    SELECT user_id, COUNT(DISTINCT o.id) AS total_order, SUM(o.total) AS total_pengeluaran_belanja
    FROM orders o
    GROUP BY user_id
) b ON b.user_id = u.id
WHERE u.is_admin = 0
ORDER BY u.id;
```

---

## Catatan

- `repair_orders.user_id` = user yang login (bisa berbeda dengan `customer_id` yang mencatat data pelanggan bengkel)
- `orders.user_id` = user yang melakukan pembelian
- `order_items.itemable_type = 'App\\Models\\Product'` = item produk (bisa juga `'App\\Models\\Service'` untuk layanan)
- `repair_orders.mechanic_id` bisa NULL jika belum ditugaskan