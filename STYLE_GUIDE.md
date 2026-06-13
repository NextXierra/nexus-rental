# Nexus Rental Style Guide

Catatan ini jadi acuan saat edit repo ini supaya gaya kode tetap konsisten.

## Stack

- CodeIgniter 4.
- Struktur custom modular di `app/Modules`.
- Frontend pakai Bootstrap 4, Font Awesome 4, CSS custom di `public/assets/css`.

## Struktur Folder

- Controller module: `app/Modules/<Module>/Controllers/<Name>.php`.
- Model module: `app/Modules/<Module>/Models/<Name>.php`.
- View module: `app/Modules/<Module>/Views/<name>.php`.
- Route utama: `app/Config/Routes.php`.
- Asset publik: `public/assets/css`, `public/assets/js`, `public/images`.
- Migration: `app/Database/Migrations`.
- Seeder: `app/Database/Seeds`.

## PHP

- Pakai `<?php`, blank line, lalu `namespace`.
- Namespace module: `Modules\<Module>\Controllers`, `Modules\<Module>\Models`.
- Controller extend `App\Controllers\BaseController`.
- Model extend `CodeIgniter\Model`.
- Indent 4 spasi.
- Pakai short array syntax `[]`.
- Ikuti alignment yang sudah ada untuk array/property jika file sekitar memakai alignment.
- Method controller boleh tanpa return type, kecuali file sekitar sudah memakai return type.
- View module dipanggil seperti `view('Modules\Login\Views\login')`.
- Redirect pakai `redirect()->to(...)` atau `redirect()->back()->withInput()`.
- Validasi form langsung di controller pakai `$rules` dan `$this->validate($rules)`.
- Session pakai `session()`.
- Escape output view pakai `esc()`.

## Routes

- Route ditulis eksplisit di `app/Config/Routes.php`.
- Format controller module pakai leading slash namespace:
  ```php
  $routes->get('login', '\Modules\Login\Controllers\Login::index');
  $routes->post('login/process', '\Modules\Login\Controllers\Login::processLogin');
  ```

## Models

- Return type model pakai array.
- Protected fields mengikuti gaya CI4 default.
- Password hashing lewat callback model jika terkait `users`.
- Field `users.role` memakai nilai `admin`, `pelanggan`.
- Database name tetap `pemweb`.
- Schema utama: `users`, `pelanggan`, `unit_ps`, `reservasi`, `pembayaran`.

## Views

- View berupa full HTML, belum pakai shared layout.
- Pakai Bootstrap utility class yang sudah ada.
- PHP control structure pakai syntax alternatif:
  ```php
  <?php if ($condition): ?>
      ...
  <?php endif; ?>

  <?php foreach ($items as $item): ?>
      ...
  <?php endforeach ?>
  ```
- Form pakai `csrf_field()`.
- Input lama pakai `old('field')`.
- Flash message pakai `session()->getFlashdata(...)`.
- Link auth banyak pakai `base_url(...)`; landing page banyak pakai path langsung seperti `/assets/...`.
- Teks UI campur Indonesia dan Inggris sesuai file sekitar.

## CSS / UI

- Tema utama: dark Playstation rental.
- Warna utama:
  - Black: `#000`, `#111`, `#222`.
  - Accent orange: `#E8890A`.
  - Orange hover: `#c77608`.
- Font:
  - Heading/nav: `Cabin`.
  - Body: `Lora`.
- Visual: uppercase, orange accent, border tipis, card gelap, image grayscale hover.
- `landingpage.css` banyak selector satu baris; ikuti gaya file itu saat edit landing.
- `auth.css` pakai multiline normal; ikuti gaya file itu saat edit auth.

## Naming

- Class controller/model singular: `Login`, `Home`, `User`.
- Variable camelCase: `$userModel`, `$loginInput`, `$playstationCards`, `$psGames`.
- DB table mengikuti schema rental: `users`, `pelanggan`, `unit_ps`, `reservasi`, `pembayaran`.
- Route URL lowercase dan sederhana: `login/process`, `register/process`.

## Komentar

- Komentar sedikit saja.
- Kalau perlu, boleh Bahasa Indonesia singkat mengikuti gaya existing.
- Jangan tambah komentar untuk hal yang jelas.

## Prinsip Edit

- Buat perubahan paling kecil yang benar.
- Jangan ganti arsitektur tanpa kebutuhan jelas.
- Jangan tambah layout/helper/service baru kalau cukup di file existing.
- Ikuti gaya file sekitar lebih penting daripada gaya pribadi.
