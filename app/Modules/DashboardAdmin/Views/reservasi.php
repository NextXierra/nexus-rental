<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi - Nexus Rental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=4">
</head>
<body class="dashboard-body">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <a href="/dashboard/admin"><i class="fa fa-home"></i> Home Dashboard</a>
            <a href="/dashboard/admin/unit-ps"><i class="fa fa-gamepad"></i> Unit PS</a>
            <a href="/dashboard/admin/reservasi" class="active"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
            <a href="#"><i class="fa fa-users"></i> Pelanggan</a>
            <a href="#"><i class="fa fa-credit-card"></i> Pembayaran</a>
            <a href="#"><i class="fa fa-bar-chart"></i> Laporan</a>
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
                <a href="#" class="dashboard-profile" aria-label="Profil"><i class="fa fa-user"></i></a>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="dashboard-page-title">Reservasi</h1>
                    <p class="dashboard-page-subtitle">Kelola transaksi reservasi Playstation.</p>
                </div>
                <button class="dashboard-button" type="button" data-toggle="modal" data-target="#createReservasiModal">
                    <i class="fa fa-plus"></i> Tambah Reservasi
                </button>
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

            <div class="dashboard-panel">
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Unit PS</th>
                                <th>Tipe</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Total Jam</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reservations): ?>
                                <?php foreach ($reservations as $res): ?>
                                    <tr>
                                        <td><?= esc($res['nama_pelanggan']) ?></td>
                                        <td><?= esc($res['nama_unit']) ?></td>
                                        <td><span class="badge badge-secondary"><?= esc($res['tipe']) ?></span></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_mulai'])) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_selesai'])) ?></td>
                                        <td><?= esc($res['total_jam']) ?> Jam</td>
                                        <td>Rp <?= number_format((int) $res['total_harga'], 0, ',', '.') ?></td>
                                        <td><span class="status-pill <?= esc($res['status']) ?>"><?= esc($res['status']) ?></span></td>
                                        <td class="text-right">
                                            <?php if ($res['status'] === 'aktif'): ?>
                                                <form action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/complete" method="post" class="d-inline" onsubmit="return confirm('Selesaikan reservasi?')">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm btn-outline-success" type="submit">Selesai</button>
                                                </form>
                                                <form action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/cancel" method="post" class="d-inline" onsubmit="return confirm('Batalkan reservasi?')">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Batal</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data reservasi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="modal fade" id="createReservasiModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/dashboard/admin/reservasi/store" method="post" class="modal-content dashboard-modal">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Reservasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pelanggan</label>
                    <select name="pelanggan_id" class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelangganList as $p): ?>
                            <option value="<?= esc($p['id']) ?>" <?= old('pelanggan_id') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unit PS (Hanya yang tersedia)</label>
                    <select name="unit_id" class="form-control" required>
                        <option value="">-- Pilih Unit --</option>
                        <?php foreach ($unitList as $u): ?>
                            <option value="<?= esc($u['id']) ?>" <?= old('unit_id') == $u['id'] ? 'selected' : '' ?>><?= esc($u['nama_unit']) ?> (Rp <?= number_format($u['harga_per_jam'], 0, ',', '.') ?>/jam)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipe Layanan</label>
                    <select name="tipe" class="form-control" required>
                        <option value="offline">Offline (Main di tempat)</option>
                        <option value="online">Online (Booking online)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control" value="<?= esc(old('waktu_mulai')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" class="form-control" value="<?= esc(old('waktu_selesai')) ?>" required>
                </div>
                <div class="form-group mb-0">
                    <label>Metode Pembayaran (Lunas Langsung)</label>
                    <select name="metode" class="form-control" required>
                        <option value="tunai">Tunai</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
