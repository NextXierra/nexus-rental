<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pendapatan - Nexus Rental</title>
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
            <a href="/dashboard/admin/pembayaran"><i class="fa fa-credit-card"></i> Pembayaran</a>
            <a href="/dashboard/admin/laporan" class="active"><i class="fa fa-bar-chart"></i> Laporan</a>
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
                    <h1 class="dashboard-page-title">Laporan Pendapatan</h1>
                    <p class="dashboard-page-subtitle">Periode: <strong><?= esc($label) ?></strong></p>
                </div>
                <div class="d-flex flex-wrap mt-2 mt-sm-0">
                    <a href="/dashboard/admin/laporan/print?filter=<?= esc($filter) ?>&tanggal=<?= esc($tanggal) ?>&minggu=<?= esc($minggu) ?>&bulan=<?= esc($bulan) ?>" target="_blank" class="dashboard-button mr-2 mb-2" style="background-color: var(--turquoise); color: var(--primary);">
                        <i class="fa fa-print"></i> Cetak PDF
                    </a>
                    <a href="/dashboard/admin/laporan/export/excel?filter=<?= esc($filter) ?>&tanggal=<?= esc($tanggal) ?>&minggu=<?= esc($minggu) ?>&bulan=<?= esc($bulan) ?>" class="dashboard-button mb-2" style="background-color: #28a745; color: white;">
                        <i class="fa fa-file-excel-o"></i> Ekspor Excel
                    </a>
                </div>
            </div>

            <!-- Form Filter Laporan -->
            <div class="dashboard-panel mb-4">
                <div class="panel-heading">
                    <h2>Filter Laporan</h2>
                    <span>Pilih periode laporan pendapatan</span>
                </div>
                <div class="p-4">
                    <form action="/dashboard/admin/laporan" method="get">
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                                <label class="d-block font-weight-bold text-uppercase" style="font-size: 12px; color: var(--primary);">Periode Laporan</label>
                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" style="border: var(--border-rough); border-radius: 4px; box-shadow: 2px 2px 0 0 rgba(0,0,0,1);">
                                    <label class="btn btn-outline-warning text-dark flex-fill font-weight-bold active">
                                        <input type="radio" name="filter" value="harian" <?= $filter === 'harian' ? 'checked' : '' ?>> Harian
                                    </label>
                                    <label class="btn btn-outline-warning text-dark flex-fill font-weight-bold">
                                        <input type="radio" name="filter" value="mingguan" <?= $filter === 'mingguan' ? 'checked' : '' ?>> Mingguan
                                    </label>
                                    <label class="btn btn-outline-warning text-dark flex-fill font-weight-bold">
                                        <input type="radio" name="filter" value="bulanan" <?= $filter === 'bulanan' ? 'checked' : '' ?>> Bulanan
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0" id="group-harian">
                                <label class="font-weight-bold text-uppercase" style="font-size: 12px; color: var(--primary);">Pilih Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal) ?>" style="border: var(--border-rough); box-shadow: 2px 2px 0 0 rgba(0,0,0,1); background-color: var(--background);">
                            </div>
                            
                            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 d-none" id="group-mingguan">
                                <label class="font-weight-bold text-uppercase" style="font-size: 12px; color: var(--primary);">Pilih Tanggal (Minggu Terkait)</label>
                                <input type="date" name="minggu" class="form-control" value="<?= esc($minggu) ?>" style="border: var(--border-rough); box-shadow: 2px 2px 0 0 rgba(0,0,0,1); background-color: var(--background);">
                            </div>
                            
                            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 d-none" id="group-bulanan">
                                <label class="font-weight-bold text-uppercase" style="font-size: 12px; color: var(--primary);">Pilih Bulan</label>
                                <input type="month" name="bulan" class="form-control" value="<?= esc($bulan) ?>" style="border: var(--border-rough); box-shadow: 2px 2px 0 0 rgba(0,0,0,1); background-color: var(--background);">
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <button type="submit" class="dashboard-button w-100" style="padding: 10px 24px;">
                                    <i class="fa fa-filter"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0">
                    <div class="dashboard-card metric-card">
                        <span>TOTAL PENDAPATAN</span>
                        <strong>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong>
                        <p>Pendapatan terakumulasi</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0">
                    <div class="dashboard-card metric-card">
                        <span>TOTAL TRANSAKSI</span>
                        <strong><?= $total_transaksi ?></strong>
                        <p>Transaksi selesai & lunas</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4 mb-sm-0">
                    <div class="dashboard-card metric-card">
                        <span>PEMBAYARAN TUNAI</span>
                        <strong><?= $tunai_count ?></strong>
                        <p>Transaksi via tunai</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card metric-card">
                        <span>PEMBAYARAN QRIS</span>
                        <strong><?= $qris_count ?></strong>
                        <p>Transaksi via QRIS</p>
                    </div>
                </div>
            </div>

            <!-- Detail Table -->
            <div class="dashboard-panel">
                <div class="panel-heading">
                    <h2>Rincian Transaksi Pendapatan</h2>
                    <span>Daftar transaksi pembayaran lunas periode ini</span>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Waktu Pembayaran</th>
                                <th>Pelanggan</th>
                                <th>Unit PS</th>
                                <th>Tipe Sewa</th>
                                <th>Durasi</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments): ?>
                                <?php $no = 1; foreach ($payments as $pay): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d M Y H:i', strtotime($pay['dibayar_at'])) ?></td>
                                        <td><?= esc($pay['nama_pelanggan']) ?></td>
                                        <td><?= esc($pay['nama_unit']) ?></td>
                                        <td><span class="badge badge-secondary"><?= esc(strtoupper($pay['tipe_sewa'])) ?></span></td>
                                        <td><?= esc($pay['total_jam']) ?> Jam</td>
                                        <td><span class="badge badge-warning"><?= esc(strtoupper($pay['metode'])) ?></span></td>
                                        <td><span class="payment-pill"><?= esc($pay['status']) ?></span></td>
                                        <td class="text-right font-weight-bold">Rp <?= number_format((int) $pay['jumlah'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">Belum ada data pendapatan untuk periode ini.</td>
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
<script>
$(document).ready(function() {
    function toggleFilters() {
        var selectedFilter = $('input[name="filter"]:checked').val();
        
        $('#group-harian').addClass('d-none');
        $('#group-mingguan').addClass('d-none');
        $('#group-bulanan').addClass('d-none');
        
        if (selectedFilter === 'harian') {
            $('#group-harian').removeClass('d-none');
        } else if (selectedFilter === 'mingguan') {
            $('#group-mingguan').removeClass('d-none');
        } else if (selectedFilter === 'bulanan') {
            $('#group-bulanan').removeClass('d-none');
        }
    }

    $('input[name="filter"]').change(toggleFilters);
    
    // Set active class properly based on selected input on page load
    var currentFilter = "<?= esc($filter) ?>";
    $('input[name="filter"][value="' + currentFilter + '"]').parent().addClass('active').siblings().removeClass('active');
    
    toggleFilters();
});
</script>
</body>
</html>
