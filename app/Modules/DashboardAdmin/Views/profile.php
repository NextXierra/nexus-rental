<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Profil Admin - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <div>
        <h1 class="dashboard-page-title">Profil Admin</h1>
        <p class="dashboard-page-subtitle">Halaman detail profil administrator.</p>
    </div>
</div>
<div class="dashboard-panel">
    <div class="p-4 bg-white" style="border-bottom: 2px solid var(--primary);">
        <p style="font-family: var(--font-body); font-size: 16px;">Selamat datang di halaman manajemen profil Administrator. Data profil Admin dikelola melalui file konfigurasi sistem dan database users.</p>
    </div>
</div>
<?= $this->endSection() ?>
