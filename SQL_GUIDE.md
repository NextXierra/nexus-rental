# SQL Guide

Database yang dipakai project ini: `pemweb`.

Konfigurasi koneksi ada di `.env` lokal. File `.env` tidak ikut commit.

## Cara Setup Database

Jalankan migration:

```bash
php spark migrate
```

Jalankan seeder default:

```bash
php spark db:seed UserSeeder
php spark db:seed GameSeeder
```

## Tabel

Schema utama:

- `users`
- `pelanggan`
- `unit_ps`
- `games`
- `reservasi`
- `pembayaran`

## `users`

Tabel akun login.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `nama` VARCHAR(100), wajib.
- `email` VARCHAR(100), wajib, unique.
- `password` VARCHAR(255), wajib, hashed dari model `UserModel`.
- `no_hp` VARCHAR(20), nullable.
- `role` ENUM(`admin`, `pelanggan`), default `pelanggan`.
- `created_at` DATETIME, default `CURRENT_TIMESTAMP`.

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

## `unit_ps`

Tabel unit Playstation.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `nama_unit` VARCHAR(50), wajib.
- `tipe` ENUM(`PS4`, `PS5`), wajib.
- `harga_per_jam` INT, wajib.
- `status` ENUM(`tersedia`, `disewa`, `maintenance`), default `tersedia`.

## `games`

Tabel master data game Playstation.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `nama_game` VARCHAR(100), wajib.
- `gambar` VARCHAR(255), wajib.
- `created_at` DATETIME, default `CURRENT_TIMESTAMP`.

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
- `status` ENUM(`pending`, `aktif`, `selesai`, `dibatalkan`), default `aktif`.
- `created_at` DATETIME, default `CURRENT_TIMESTAMP`.

Relasi:

- `reservasi.pelanggan_id` ke `pelanggan.id`.
- `reservasi.unit_id` ke `unit_ps.id`.

## `pembayaran`

Tabel pembayaran reservasi.

Kolom:

- `id` INT unsigned, primary key, auto increment.
- `reservasi_id` INT unsigned, wajib, unique.
- `jumlah` INT, wajib.
- `metode` ENUM(`tunai`, `qris`), default `tunai`.
- `status` ENUM(`belum_bayar`, `sudah_bayar`, `lunas`), default `lunas`.
- `dibayar_at` DATETIME, default `CURRENT_TIMESTAMP`.

Relasi:

- `pembayaran.reservasi_id` ke `reservasi.id`.

## File Terkait

- Migrations: 
  * `app/Database/Migrations/2026-06-12-162518_CreateUsersTable.php`
  * `app/Database/Migrations/2026-06-13-053750_AlterReservasiPembayaranStatus.php`
  * `app/Database/Migrations/2026-06-15-154126_CreateGamesTable.php`
- Seeders:
  * `app/Database/Seeds/UserSeeder.php`
  * `app/Database/Seeds/GameSeeder.php`
- Models:
  * `app/Modules/Login/Models/UserModel.php`
  * `app/Modules/DashboardAdmin/Models/GameModel.php`
