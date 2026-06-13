<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unit PS - Nexus Rental</title>
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
            <a href="/dashboard/admin/unit-ps" class="active"><i class="fa fa-gamepad"></i> Unit PS</a>
            <a href="/dashboard/admin/reservasi"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
            <a href="#"><i class="fa fa-users"></i> Pelanggan</a>
            <a href="/dashboard/admin/pembayaran"><i class="fa fa-credit-card"></i> Pembayaran</a>
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
                    <h1 class="dashboard-page-title">Unit PS</h1>
                    <p class="dashboard-page-subtitle">Kelola data Playstation rental.</p>
                </div>
                <button class="dashboard-button" type="button" data-toggle="modal" data-target="#createUnitModal">
                    <i class="fa fa-plus"></i> Tambah Unit
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
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
                                <th>Nama Unit</th>
                                <th>Tipe</th>
                                <th>Harga/Jam</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($units): ?>
                                <?php foreach ($units as $unit): ?>
                                    <tr>
                                        <td><?= esc($unit['nama_unit']) ?></td>
                                        <td><?= esc($unit['tipe']) ?></td>
                                        <td>Rp <?= number_format((int) $unit['harga_per_jam'], 0, ',', '.') ?></td>
                                        <td><span class="status-pill <?= esc($unit['status']) ?>"><?= esc($unit['status']) ?></span></td>
                                        <td class="text-right">
                                            <button class="btn btn-sm btn-outline-warning" type="button" data-toggle="modal" data-target="#editUnitModal<?= esc($unit['id']) ?>">Edit</button>
                                            <form action="/dashboard/admin/unit-ps/<?= esc($unit['id']) ?>/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus unit ini?')">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada unit PS.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="modal fade" id="createUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/dashboard/admin/unit-ps/store" method="post" class="modal-content dashboard-modal">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit PS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Unit</label>
                    <input type="text" name="nama_unit" class="form-control" value="<?= esc(old('nama_unit')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control" required>
                        <option value="PS4">PS4</option>
                        <option value="PS5">PS5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga per Jam</label>
                    <input type="number" name="harga_per_jam" class="form-control" value="<?= esc(old('harga_per_jam')) ?>" min="1" required>
                </div>
                <div class="form-group mb-0">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="tersedia">tersedia</option>
                        <option value="disewa">disewa</option>
                        <option value="maintenance">maintenance</option>
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

<?php foreach ($units as $unit): ?>
    <div class="modal fade" id="editUnitModal<?= esc($unit['id']) ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/dashboard/admin/unit-ps/<?= esc($unit['id']) ?>/update" method="post" class="modal-content dashboard-modal">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Unit PS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Unit</label>
                        <input type="text" name="nama_unit" class="form-control" value="<?= esc($unit['nama_unit']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe</label>
                        <select name="tipe" class="form-control" required>
                            <option value="PS4" <?= $unit['tipe'] === 'PS4' ? 'selected' : '' ?>>PS4</option>
                            <option value="PS5" <?= $unit['tipe'] === 'PS5' ? 'selected' : '' ?>>PS5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga per Jam</label>
                        <input type="number" name="harga_per_jam" class="form-control" value="<?= esc($unit['harga_per_jam']) ?>" min="1" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="tersedia" <?= $unit['status'] === 'tersedia' ? 'selected' : '' ?>>tersedia</option>
                            <option value="disewa" <?= $unit['status'] === 'disewa' ? 'selected' : '' ?>>disewa</option>
                            <option value="maintenance" <?= $unit['status'] === 'maintenance' ? 'selected' : '' ?>>maintenance</option>
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
<?php endforeach; ?>

<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
