# SQL Guide

Database yang dipakai project ini: `pemweb`.

Konfigurasi koneksi ada di `.env` lokal. File `.env` tidak ikut commit.

## Cara Setup Database

Jalankan migration:

```bash
php spark migrate
```

Jalankan seeder user default:

```bash
php spark db:seed UserSeeder
```

## Tabel

Schema utama:

- `users`
- `pelanggan`
- `unit_ps`
- `reservasi`
- `pembayaran`

## `users`

Tabel akun login.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `nama` VARCHAR(100), wajib.
- `email` VARCHAR(100), wajib, unique.
- `password` VARCHAR(255), wajib, hashed dari model `User`.
- `no_hp` VARCHAR(20), nullable.
- `role` ENUM(`admin`, `pelanggan`), default `pelanggan`.
- `created_at` DATETIME, default `CURRENT_TIMESTAMP`.

Catatan:

- Login pakai `email` dan `password`.
- Session menyimpan `user_id`, `nama`, `role`, `logged_in`.
- Role `admin` redirect ke `/dashboard/admin`.
- Role `pelanggan` redirect ke `/dashboard/user`.

## `pelanggan`

Tabel data pelanggan online dan offline.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `user_id` INT unsigned, nullable.
- `nama` VARCHAR(100), wajib.
- `no_hp` VARCHAR(20), nullable.

Relasi:

- `pelanggan.user_id` ke `users.id`.
- On delete: `SET NULL`.

Catatan:

- Pelanggan online bisa punya `user_id`.
- Pelanggan offline boleh `user_id = NULL`.

## `unit_ps`

Tabel unit Playstation.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `nama_unit` VARCHAR(50), wajib.
- `tipe` ENUM(`PS4`, `PS5`), wajib.
- `harga_per_jam` INT, wajib.
- `status` ENUM(`tersedia`, `disewa`, `maintenance`), default `tersedia`.

Catatan status:

- `tersedia`: unit bisa disewa.
- `disewa`: unit sedang dipakai.
- `maintenance`: unit tidak tersedia karena perawatan.

## `reservasi`

Tabel transaksi reservasi.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `pelanggan_id` INT unsigned, wajib.
- `unit_id` INT unsigned, wajib.
- `tipe` ENUM(`online`, `offline`), wajib.
- `waktu_mulai` DATETIME, wajib.
- `waktu_selesai` DATETIME, wajib.
- `total_jam` INT, wajib.
- `harga_per_jam` INT, wajib.
- `total_harga` INT, wajib.
- `status` ENUM(`aktif`, `selesai`, `dibatalkan`), default `aktif`.
- `created_at` DATETIME, default `CURRENT_TIMESTAMP`.

Relasi:

- `reservasi.pelanggan_id` ke `pelanggan.id`.
- `reservasi.unit_id` ke `unit_ps.id`.

Catatan:

- `harga_per_jam` adalah snapshot harga unit saat reservasi dibuat.
- `total_jam` dihitung dari `waktu_mulai` sampai `waktu_selesai`.
- `total_harga` dihitung dari `total_jam * harga_per_jam`.

## `pembayaran`

Tabel pembayaran reservasi.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `reservasi_id` INT unsigned, wajib, unique.
- `jumlah` INT, wajib.
- `metode` ENUM(`tunai`, `qris`), default `tunai`.
- `status` ENUM(`lunas`), default `lunas`.
- `dibayar_at` DATETIME, default `CURRENT_TIMESTAMP`.

Relasi:

- `pembayaran.reservasi_id` ke `reservasi.id`.

Catatan:

- Satu reservasi punya satu pembayaran.
- Metode pembayaran hanya `tunai` atau `qris`.
- Status pembayaran saat ini hanya `lunas`.

## Flow Reservasi

Saat reservasi dibuat:

1. Ambil `harga_per_jam` dari `unit_ps`.
2. Hitung `total_jam` dari `waktu_mulai` dan `waktu_selesai`.
3. Hitung `total_harga = total_jam * harga_per_jam`.
4. Insert ke `reservasi`.
5. Insert ke `pembayaran` dengan `jumlah = total_harga`.
6. Update `unit_ps.status` jadi `disewa`.

Saat reservasi selesai:

1. Update `reservasi.status` jadi `selesai`.
2. Update `unit_ps.status` jadi `tersedia`.

Saat reservasi dibatalkan:

1. Update `reservasi.status` jadi `dibatalkan`.
2. Update `unit_ps.status` jadi `tersedia` jika unit sebelumnya `disewa`.

## Query Contoh

Laporan pendapatan harian:

```sql
SELECT
    DATE(r.created_at) AS tanggal,
    COUNT(r.id)        AS jumlah_transaksi,
    SUM(p.jumlah)      AS total_pendapatan
FROM pembayaran p
JOIN reservasi r ON p.reservasi_id = r.id
GROUP BY DATE(r.created_at)
ORDER BY tanggal DESC;
```

Reservasi aktif:

```sql
SELECT
    r.id,
    p.nama AS pelanggan,
    u.nama_unit,
    r.waktu_mulai,
    r.waktu_selesai,
    r.total_harga,
    r.status
FROM reservasi r
JOIN pelanggan p ON r.pelanggan_id = p.id
JOIN unit_ps u ON r.unit_id = u.id
WHERE r.status = 'aktif'
ORDER BY r.waktu_mulai ASC;
```

Unit tersedia:

```sql
SELECT id, nama_unit, tipe, harga_per_jam
FROM unit_ps
WHERE status = 'tersedia'
ORDER BY tipe, nama_unit;
```

## File Terkait

- Migration: `app/Database/Migrations/2026-06-12-162518_CreateUsersTable.php`.
- Seeder user: `app/Database/Seeds/UserSeeder.php`.
- Model user: `app/Modules/Login/Models/User.php`.
- Login controller: `app/Modules/Login/Controllers/Login.php`.
