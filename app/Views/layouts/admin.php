<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ? $this->renderSection('title') : 'Dashboard Admin - Nexus Rental' ?></title>
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=6">
    <?= $this->renderSection('styles') ?>
</head>
<body class="dashboard-body">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <?php
            $uri = service('uri');
            $segment = $uri->getSegment(3); // dashboard/admin/segment
            ?>
            <a href="/dashboard/admin" class="<?= empty($segment) ? 'active' : '' ?>"><i class="fa fa-home"></i> Home Dashboard</a>
            <a href="/dashboard/admin/unit-ps" class="<?= ($segment === 'unit-ps') ? 'active' : '' ?>"><i class="fa fa-gamepad"></i> Unit PS</a>
            <a href="/dashboard/admin/games" class="<?= ($segment === 'games') ? 'active' : '' ?>"><i class="fa fa-th-large"></i> Games</a>
            <a href="/dashboard/admin/reservasi" class="<?= ($segment === 'reservasi') ? 'active' : '' ?>"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
            <a href="/dashboard/admin/pelanggan" class="<?= ($segment === 'pelanggan') ? 'active' : '' ?>"><i class="fa fa-users"></i> Pelanggan</a>
            <a href="/dashboard/admin/pembayaran" class="<?= ($segment === 'pembayaran') ? 'active' : '' ?>"><i class="fa fa-credit-card"></i> Pembayaran</a>
            <a href="/dashboard/admin/laporan" class="<?= ($segment === 'laporan') ? 'active' : '' ?>"><i class="fa fa-bar-chart"></i> Laporan</a>
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
            <?= $this->renderSection('content') ?>
        </section>
    </main>
</div>
<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
