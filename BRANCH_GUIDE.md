# Branch Guide

Branch utama kerja dashboard saat ini: `dashboard`.

Setiap menu navbar punya branch sendiri supaya tiap orang bisa kerja terpisah. Semua branch dibuat dari commit `7166fce` di branch `dashboard`.

## Admin

- `admin-home-dashboard` untuk menu Home Dashboard.
- `admin-unit-ps` untuk menu Unit PS.
- `admin-reservasi` untuk menu Reservasi.
- `admin-pelanggan` untuk menu Pelanggan.
- `admin-pembayaran` untuk menu Pembayaran.
- `admin-laporan` untuk menu Laporan.

## User

- `user-home` untuk menu Home.
- `user-reservasi-saya` untuk menu Reservasi Saya.
- `user-profil` untuk menu Profil.

## Alur Kerja

1. Ambil branch sesuai menu yang dikerjakan.
2. Kerjakan fitur hanya di branch itu.
3. Push branch masing-masing ke remote.
4. Merge dilakukan manual ke branch `dashboard`.

## Catatan Dashboard

- Module admin ada di `app/Modules/DashboardAdmin`.
- Module user ada di `app/Modules/DashboardUser`.
- Style dashboard bersama ada di `public/assets/css/dashboard.css`.
- Navbar admin ada di `app/Modules/DashboardAdmin/Views/dashboard.php`.
- Navbar user ada di `app/Modules/DashboardUser/Views/dashboard.php`.
