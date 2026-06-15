<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pembayaran - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <div>
        <h1 class="dashboard-page-title">Pembayaran</h1>
        <p class="dashboard-page-subtitle">Riwayat pembayaran lunas reservasi.</p>
    </div>
</div>

<div class="dashboard-panel">
    <div class="table-responsive">
        <table class="table dashboard-table mb-0">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Unit PS</th>
                    <th>Metode</th>
                    <th>Jumlah Bayar</th>
                    <th>Status</th>
                    <th>Waktu Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($payments): ?>
                    <?php foreach ($payments as $pay): ?>
                        <tr>
                            <td><?= esc($pay['nama_pelanggan']) ?></td>
                            <td><?= esc($pay['nama_unit']) ?></td>
                            <td><span class="badge badge-warning"><?= esc(strtoupper($pay['metode'])) ?></span></td>
                            <td>Rp <?= number_format((int) $pay['jumlah'], 0, ',', '.') ?></td>
                            <td><span class="payment-pill"><?= esc($pay['status']) ?></span></td>
                            <td><?= date('d M Y H:i', strtotime($pay['dibayar_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)): ?>
        <div class="d-flex justify-content-center pt-3 pb-3">
            <?= $pager->links('payments', 'brutal') ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
