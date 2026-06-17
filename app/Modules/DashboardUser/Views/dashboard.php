<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Dashboard User - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-header user-hero mb-4">
    <p class="dashboard-kicker">Selamat Datang!</p>
    <h1>Hai, <?= esc(session()->get('nama') ?? 'Kawan') ?>!</h1>
    <p>Siap untuk bersenang-senang hari ini? Cari konsol PlayStation favoritmu di katalog bawah dan mulai petualangan bermainmu bersama Nexus Rental!</p>
    <a href="/dashboard/user/reservasi?book=1" class="dashboard-button mt-3"><i class="fa fa-plus-circle"></i> Booking Sekarang</a>
</div>

<div class="row mb-4">
    <div class="col-sm-6 col-lg-4 mb-3">
        <div class="dashboard-card metric-card">
            <span>Total Booking Saya</span>
            <strong><?= esc($totalReservations) ?></strong>
            <p><i class="fa fa-history text-muted"></i> Riwayat sewa keseluruhan</p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-3">
        <div class="dashboard-card metric-card">
            <span>Booking Aktif / Pending</span>
            <strong><?= esc($activeReservationsCount) ?></strong>
            <p><i class="fa fa-refresh text-warning"></i>/ menunggu</p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-3">
        <div class="dashboard-card metric-card">
            <span>Total Pembayaran Lunas</span>
            <strong>Rp <?= number_format($totalSpent, 0, ',', '.') ?></strong>
            <p><i class="fa fa-check-circle text-success"></i> Transaksi terverifikasi</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="dashboard-panel mb-0" style="border-bottom: none;">
            <div class="panel-heading">
                <h2>Katalog Unit PS</h2>
                <span>Konsol Siap Sewa Hari Ini</span>
            </div>
        </div>
        <div class="row mt-3">
            <?php if ($availableUnitsList): ?>
                <?php foreach ($availableUnitsList as $unit): ?>
                    <div class="col-sm-6 mb-3">
                        <div class="dashboard-card available-card h-100 p-3 d-flex flex-column justify-content-between" style="border: var(--border-rough); background-color: var(--surface-container-lowest); box-shadow: var(--shadow-block); min-height: 200px;">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge" style="background-color: var(--turquoise); color: var(--primary); border: 1px solid var(--primary); font-family: var(--font-body); font-size: 11px;"><?= esc($unit['tipe']) ?></span>
                                    <span class="status-pill tersedia" style="font-size: 9px; padding: 2px 6px;">READY</span>
                                </div>
                                <h3 class="my-2" style="font-family: var(--font-headline); font-size: 2.2rem; font-weight: bold; margin-top: 8px;"><?= esc($unit['nama_unit']) ?></h3>
                                <p class="text-muted mb-2" style="font-size: 13px; font-family: var(--font-body);">Nikmati pengalaman gaming premium dengan tarif hemat.</p>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px dashed var(--outline-variant);">
                                    <div>
                                        <span style="font-size: 10px; text-transform: uppercase; color: var(--on-surface-variant); display: block; font-family: var(--font-body);">Tarif</span>
                                        <strong style="color: var(--burnt-orange); font-size: 15px; font-family: var(--font-body);">Rp <?= number_format($unit['harga_per_jam'], 0, ',', '.') ?>/jam</strong>
                                    </div>
                                    <a href="/dashboard/user/reservasi?book=1&unit_id=<?= esc($unit['id']) ?>" class="btn btn-sm" style="border: var(--border-rough); background-color: var(--burnt-orange); color: white; font-weight: bold; font-family: var(--font-headline); font-size: 1.4rem; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);">Sewa</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5 text-muted" style="border: var(--border-rough); background-color: var(--surface-container-low); border-radius: 8px; box-shadow: var(--shadow-block);">
                        <i class="fa fa-gamepad fa-3x mb-2" style="opacity: 0.5; color: var(--primary);"></i>
                        <p class="mb-0" style="font-family: var(--font-body); font-size: 14px; font-weight: bold;">Semua unit PS sedang disewa. Silakan cek berkala!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="dashboard-panel">
            <div class="panel-heading">
                <h2>Booking Terkini</h2>
                <span>Status Transaksi</span>
            </div>
            <div class="booking-list">
                <?php if ($myReservationsList): ?>
                    <?php foreach ($myReservationsList as $res): 
                        $statusClass = 'active';
                        $statusLabel = 'AKTIF';
                        if ($res['status'] === 'pending') {
                            $statusClass = 'maintenance';
                            $statusLabel = 'PENDING';
                        } elseif ($res['status'] === 'selesai') {
                            $statusClass = 'selesai';
                            $statusLabel = 'SELESAI';
                        } elseif ($res['status'] === 'dibatalkan') {
                            $statusClass = 'dibatalkan';
                            $statusLabel = 'BATAL';
                        }
                        
                        $isPaid = ($res['status_bayar'] === 'lunas' || $res['status_bayar'] === 'sudah_bayar');
                    ?>
                        <div class="booking-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="mt-0 mb-1" style="font-size: 1.8rem; font-family: var(--font-headline);"><?= esc($res['nama_unit']) ?> <small>(<?= esc($res['tipe_unit']) ?>)</small></h3>
                                    <div class="booking-meta d-flex flex-column gap-1" style="font-size: 13px; font-family: var(--font-body);">
                                        <span>Jadwal: <strong><?= date('d M Y H:i', strtotime($res['waktu_mulai'])) ?></strong></span>
                                        <span>Durasi: <strong><?= esc($res['total_jam']) ?> jam</strong> (Rp <?= number_format($res['total_harga'], 0, ',', '.') ?>)</span>
                                        <span>Pembayaran: 
                                            <span class="badge badge-warning" style="font-size: 9px; border: 1px solid var(--primary);"><?= strtoupper(esc($res['metode_bayar'] ?? 'TUNAI')) ?></span> 
                                            - 
                                            <strong style="color: <?= $isPaid ? 'var(--available-green)' : 'var(--occupied-red)' ?>;">
                                                <?= strtoupper(esc($res['status_bayar'] ?? 'belum_bayar')) ?>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="status-pill <?= $statusClass ?>" style="font-size: 9px; padding: 2px 6px;"><?= $statusLabel ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-calendar-o fa-2x mb-2" style="opacity: 0.5; color: var(--primary);"></i>
                        <p class="mb-0" style="font-family: var(--font-body); font-size: 14px;">Belum ada riwayat booking.</p>
                        <a href="/dashboard/user/reservasi" class="btn btn-sm mt-3" style="border: var(--border-rough); background-color: var(--turquoise); color: var(--primary); font-weight: bold; font-family: var(--font-headline); font-size: 1.4rem; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);">Sewa Sekarang</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
