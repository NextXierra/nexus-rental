<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Profil Saya - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
        <h1 class="dashboard-page-title">Profil Saya</h1>
        <p class="dashboard-page-subtitle">Kelola informasi akun dan kata sandi Anda.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Profile Info Card -->
    <div class="col-lg-4 mb-4">
        <div class="dashboard-panel text-center p-4" style="background-color: var(--surface-container-low);">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; border: var(--border-rough); border-radius: 999px; background-color: var(--turquoise); box-shadow: var(--shadow-block);">
                <i class="fa fa-user fa-3x text-white"></i>
            </div>
            <h2 style="font-family: var(--font-headline); font-size: 2.8rem; font-weight: bold; margin-bottom: 4px;"><?= esc($user['nama']) ?></h2>
            <span class="badge badge-secondary" style="font-family: var(--font-body); font-size: 11px; text-transform: uppercase; border: 1px solid var(--primary);"><?= esc($user['role']) ?></span>
            
            <hr style="border-top: 1px dashed var(--outline-variant); margin: 24px 0;">
            
            <div class="text-left" style="font-family: var(--font-body); font-size: 14px;">
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 11px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.05em;">Email</span>
                    <strong><?= esc($user['email']) ?></strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 11px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.05em;">No. Telepon / WhatsApp</span>
                    <strong><?= esc($user['no_hp'] ?? '-') ?></strong>
                </div>
                <div>
                    <span class="text-muted d-block" style="font-size: 11px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.05em;">Bergabung Sejak</span>
                    <strong><?= date('d M Y', strtotime($user['created_at'])) ?></strong>
                </div>
            </div>
        </div>

        <!-- Quick Tips / Support Panel -->
        <div class="dashboard-panel mt-4">
            <div class="panel-heading">
                <h2>Panduan & Bantuan</h2>
                <span>Butuh info lebih lanjut?</span>
            </div>
            <div class="p-3" style="background-color: var(--surface-container-lowest); border-bottom: var(--border-rough); font-family: var(--font-body); font-size: 13px; line-height: 1.5;">
                <ul class="list-unstyled mb-3">
                    <li class="mb-2">
                        <i class="fa fa-clock-o text-warning mr-1"></i> <strong>Tepat Waktu:</strong> Rental dihitung otomatis. Harap selesaikan sesuai jadwal.
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-qrcode text-info mr-1"></i> <strong>Pemberitahuan Bayar:</strong> Tunjukkan bukti bayar QRIS di kasir admin.
                    </li>
                    <li>
                        <i class="fa fa-gamepad text-success mr-1"></i> <strong>Peralatan PS:</strong> Harap jaga kebersihan controller dan konsol rental.
                    </li>
                </ul>
                <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-block" style="border: var(--border-rough); background-color: var(--turquoise); color: var(--primary); font-weight: bold; font-family: var(--font-headline); font-size: 1.6rem; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1); text-transform: uppercase;">
                    <i class="fa fa-whatsapp mr-1"></i> WhatsApp Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form Panel -->
    <div class="col-lg-8 mb-4">
        <form action="/dashboard/user/profil/update" method="post" class="dashboard-panel mb-0">
            <?= csrf_field() ?>
            <div class="panel-heading">
                <h2>Ubah Informasi Profil</h2>
                <span>Perbarui detail data pribadi dan kata sandi Anda</span>
            </div>
            <div class="p-4" style="background-color: var(--surface-container-lowest); border-bottom: var(--border-rough);">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary);">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" value="<?= esc(old('nama', $user['nama'])) ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary);">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" value="<?= esc(old('no_hp', $user['no_hp'])) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary);">Alamat Email</label>
                    <input type="email" name="email" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" value="<?= esc(old('email', $user['email'])) ?>" required>
                </div>

                <hr style="border-top: 1px dashed var(--outline-variant); margin: 24px 0;">
                
                <h3 class="mb-1" style="font-family: var(--font-headline); font-size: 2.2rem; font-weight: bold;">Ubah Kata Sandi</h3>
                <p class="text-muted mb-3" style="font-size: 13px; font-family: var(--font-body); margin-top: 4px;">Kosongkan jika Anda tidak ingin mengubah kata sandi.</p>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary);">Kata Sandi Baru</label>
                        <input type="password" name="password_baru" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary);">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="konfirmasi_password_baru" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" placeholder="Ulangi kata sandi baru">
                    </div>
                </div>

                <hr style="border-top: 1px dashed var(--outline-variant); margin: 24px 0;">

                <div class="form-group mb-0">
                    <label style="font-family: var(--font-body); font-size: 13px; font-weight: bold; text-transform: uppercase; color: var(--primary); display: block;">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                    <p class="text-muted mb-2" style="font-size: 12px; font-family: var(--font-body); margin-top: 4px;">Konfirmasikan kata sandi saat ini untuk menyimpan perubahan.</p>
                    <input type="password" name="password_sekarang" class="form-control shadow-none" style="border: var(--border-rough); background-color: var(--background); color: var(--primary);" placeholder="Masukkan kata sandi aktif Anda" required>
                </div>
            </div>
            <div class="p-3" style="background-color: var(--surface-container-low); display: flex; justify-content: flex-end;">
                <button type="submit" class="dashboard-button" style="border: var(--border-rough); background-color: var(--burnt-orange); color: white; box-shadow: var(--shadow-block);"><i class="fa fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
