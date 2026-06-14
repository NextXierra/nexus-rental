<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Nexus Rental</title>
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=6">
</head>
<body class="dashboard-body">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <a href="/dashboard/admin" class="active"><i class="fa fa-home"></i> Home Dashboard</a>
            <a href="/dashboard/admin/unit-ps"><i class="fa fa-gamepad"></i> Unit PS</a>
            <a href="/dashboard/admin/reservasi"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
            <a href="/dashboard/admin/pelanggan"><i class="fa fa-users"></i> Pelanggan</a>
            <a href="/dashboard/admin/pembayaran"><i class="fa fa-credit-card"></i> Pembayaran</a>
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
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="dashboard-page-title">Home Dashboard</h1>
                    <p class="dashboard-page-subtitle">Ringkasan performa dan rental Nexus Rental hari ini.</p>
                </div>
            </div>

            <!-- Metric Cards (Bento Grid) -->
            <div class="row mb-4">
                <!-- Card 1: Pendapatan Hari Ini -->
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="dashboard-card metric-card">
                        <span>Pendapatan Hari Ini</span>
                        <strong>Rp <?= number_format($todayRevenue, 0, ',', '.') ?></strong>
                        <p><i class="fa fa-money text-success"></i> Pembayaran lunas hari ini</p>
                    </div>
                </div>
                <!-- Card 2: Reservasi Aktif -->
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="dashboard-card metric-card">
                        <span>Reservasi Aktif</span>
                        <strong><?= esc($activeReservations) ?></strong>
                        <p><i class="fa fa-clock-o text-warning"></i> Rental sedang berjalan</p>
                    </div>
                </div>
                <!-- Card 3: Unit PS Tersedia -->
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="dashboard-card metric-card">
                        <span>Unit PS Tersedia</span>
                        <strong><?= esc($availableUnits) ?> <small style="font-size: 1.8rem; color: var(--primary);">/ <?= esc($totalUnits) ?></small></strong>
                        <p><i class="fa fa-gamepad text-info"></i> Konsol siap disewa</p>
                    </div>
                </div>
                <!-- Card 4: Total Pelanggan -->
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="dashboard-card metric-card">
                        <span>Total Pelanggan</span>
                        <strong><?= esc($totalCustomers) ?></strong>
                        <p><i class="fa fa-users text-primary"></i> Pelanggan terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Side: Revenue Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="dashboard-panel">
                        <div class="panel-heading">
                            <h2>Tren Pendapatan</h2>
                            <span>7 Hari Terakhir</span>
                        </div>
                        <div class="p-4 bg-white d-flex align-items-center justify-content-center" style="border-bottom: 2px solid var(--primary); min-height: 320px;">
                            <?php
                            // Calculate max amount for chart scaling
                            $maxAmount = 100000; // default minimum limit
                            foreach ($weeklyData as $data) {
                                if ($data['amount'] > $maxAmount) {
                                    $maxAmount = $data['amount'];
                                }
                            }
                            // Round up to nice clean numbers
                            if ($maxAmount > 100000) {
                                $maxAmount = ceil($maxAmount / 50000) * 50000;
                            } else {
                                $maxAmount = ceil($maxAmount / 10000) * 10000;
                            }
                            ?>
                            <svg class="w-100" viewBox="0 0 650 300" style="max-height: 320px;" xmlns="http://www.w3.org/2000/svg">
                                <!-- Background grid lines -->
                                <?php for ($grid = 0; $grid <= 4; $grid++): 
                                    $yGrid = 40 + $grid * 50;
                                    $valGrid = $maxAmount - ($grid * ($maxAmount / 4));
                                ?>
                                    <line x1="65" y1="<?= $yGrid ?>" x2="615" y2="<?= $yGrid ?>" stroke="var(--outline-variant)" stroke-dasharray="4" stroke-width="1.5" />
                                    <text x="55" y="<?= $yGrid + 4 ?>" text-anchor="end" font-family="var(--font-body)" font-size="11" fill="var(--on-surface-variant)">
                                        Rp <?= number_format($valGrid, 0, ',', '.') ?>
                                    </text>
                                <?php endfor; ?>

                                <!-- Bars -->
                                <?php foreach ($weeklyData as $index => $data):
                                    $barWidth = 46;
                                    $gap = 75;
                                    $x = 90 + $index * $gap;
                                    $amount = $data['amount'];
                                    
                                    // Max bar height is 200px (from Y=40 to Y=240)
                                    $barHeight = ($amount / $maxAmount) * 200;
                                    $y = 240 - $barHeight;
                                    
                                    $isToday = ($index === 6);
                                    $fillColor = $isToday ? 'var(--burnt-orange)' : 'var(--turquoise)';
                                    $textColor = $isToday ? 'var(--burnt-orange)' : 'var(--primary)';
                                    $textWeight = $isToday ? 'bold' : 'normal';
                                ?>
                                    <!-- Shadow Block -->
                                    <?php if ($barHeight > 0): ?>
                                        <rect x="<?= $x + 4 ?>" y="<?= $y + 4 ?>" width="<?= $barWidth ?>" height="<?= $barHeight ?>" fill="#000" rx="3" />
                                        <!-- Main Bar -->
                                        <rect x="<?= $x ?>" y="<?= $y ?>" width="<?= $barWidth ?>" height="<?= $barHeight ?>" fill="<?= $fillColor ?>" stroke="#000" stroke-width="2" rx="3" />
                                        <!-- Value Label on Top of Bar -->
                                        <text x="<?= $x + $barWidth / 2 ?>" y="<?= $y - 8 ?>" text-anchor="middle" font-family="var(--font-headline)" font-size="14" font-weight="bold" fill="<?= $textColor ?>">
                                            <?= $amount >= 1000 ? ($amount / 1000) . 'k' : $amount ?>
                                        </text>
                                    <?php else: ?>
                                        <circle cx="<?= $x + $barWidth / 2 ?>" cy="240" r="3" fill="var(--outline)" />
                                    <?php endif; ?>

                                    <!-- X Axis Labels -->
                                    <text x="<?= $x + $barWidth / 2 ?>" y="265" text-anchor="middle" font-family="var(--font-headline)" font-size="16" font-weight="<?= $textWeight ?>" fill="<?= $textColor ?>">
                                        <?= esc($data['day']) ?>
                                    </text>
                                    <text x="<?= $x + $barWidth / 2 ?>" y="282" text-anchor="middle" font-family="var(--font-body)" font-size="10" fill="var(--on-surface-variant)">
                                        <?= esc($data['date']) ?>
                                    </text>
                                <?php endforeach; ?>
                                
                                <!-- Baseline -->
                                <line x1="65" y1="240" x2="615" y2="240" stroke="#000" stroke-width="2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Active Reservations -->
                <div class="col-lg-4 mb-4">
                    <div class="dashboard-panel">
                        <div class="panel-heading">
                            <h2>Reservasi Aktif</h2>
                            <span>Sedang Berjalan</span>
                        </div>
                        <div class="booking-list">
                            <?php if ($activeReservationsList): ?>
                                <?php foreach ($activeReservationsList as $res): ?>
                                    <div class="booking-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h3 class="mt-0 mb-1" style="font-size: 1.8rem; font-family: var(--font-headline);"><?= esc($res['nama_pelanggan']) ?></h3>
                                                <div class="booking-meta d-flex align-items-center">
                                                    <span>Unit: <strong><?= esc($res['nama_unit']) ?></strong></span>
                                                    <span class="mx-2 text-muted">•</span>
                                                    <span>Mulai: <?= date('H:i', strtotime($res['waktu_mulai'])) ?></span>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="status-pill active" style="font-size: 9px; padding: 2px 6px;">AKTIF</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa fa-calendar-o fa-2x mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0" style="font-family: var(--font-body); font-size: 14px;">Tidak ada reservasi aktif saat ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
