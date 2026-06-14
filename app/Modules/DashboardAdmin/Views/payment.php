<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran - Nexus Rental</title>
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=6">
</head>
<body class="dashboard-body">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <a href="/dashboard/admin"><i class="fa fa-home"></i> Home Dashboard</a>
            <a href="/dashboard/admin/unit-ps"><i class="fa fa-gamepad"></i> Unit PS</a>
            <a href="/dashboard/admin/reservasi"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
            <a href="/dashboard/admin/pelanggan"><i class="fa fa-users"></i> Pelanggan</a>
            <a href="/dashboard/admin/pembayaran" class="active"><i class="fa fa-credit-card"></i> Pembayaran</a>
            <a href="/dashboard/admin/laporan"><i class="fa fa-bar-chart"></i> Laporan</a>
            <a href="/logout"><i class="fa fa-sign-out"></i> Logout</a>
        </nav>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-topnav">
            <form class="dashboard-search" action="#" method="get">
                <input type="text" name="search" placeholder="Search for...">
                <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
            </form>
            <div class="dashboard-user-menu">
                <span>Admin</span>
                <a href="/dashboard/admin/profil" class="dashboard-profile" aria-label="Profil"><i class="fa fa-user"></i></a>
            </div>
        </header>

        <section class="dashboard-content">
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
            </div>
        </section>
    </main>
</div>

<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
