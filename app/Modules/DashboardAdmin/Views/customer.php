<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pelanggan - Nexus Rental</title>
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
            <a href="/dashboard/admin/pelanggan" class="active"><i class="fa fa-users"></i> Pelanggan</a>
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
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="dashboard-page-title">Pelanggan</h1>
                    <p class="dashboard-page-subtitle">Kelola data pelanggan online dan offline.</p>
                </div>
                <button class="dashboard-button" type="button" data-toggle="modal" data-target="#createCustomerModal">
                    <i class="fa fa-plus"></i> Tambah Pelanggan
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
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Akun User</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($customers): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td><?= esc($customer['nama']) ?></td>
                                        <td><?= esc($customer['no_hp'] ?: '-') ?></td>
                                        <td>
                                            <?php if ($customer['user_id']): ?>
                                                <span class="badge badge-info"><i class="fa fa-user"></i> <?= esc($customer['email']) ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Offline / Non-Member</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-sm btn-outline-warning" type="button" data-toggle="modal" data-target="#editCustomerModal<?= esc($customer['id']) ?>">Edit</button>
                                            <form id="delete-form-<?= esc($customer['id']) ?>" action="/dashboard/admin/pelanggan/<?= esc($customer['id']) ?>/delete" method="post" class="d-none">
                                                <?= csrf_field() ?>
                                            </form>
                                            <button class="btn btn-sm btn-outline-danger trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menghapus pelanggan ini?" data-form-id="delete-form-<?= esc($customer['id']) ?>">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada pelanggan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/dashboard/admin/pelanggan/store" method="post" class="modal-content dashboard-modal">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc(old('nama')) ?>" required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp')) ?>">
                </div>
                <div class="form-group mb-0">
                    <label>Hubungkan Akun User (Opsional)</label>
                    <select name="user_id" class="form-control">
                        <option value="">-- Tidak Dihubungkan / Offline --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= esc($user['id']) ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
                                <?= esc($user['nama']) ?> (<?= esc($user['email']) ?>)
                            </option>
                        <?php endforeach; ?>
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

<!-- Modal Edit Pelanggan -->
<?php foreach ($customers as $customer): ?>
    <div class="modal fade" id="editCustomerModal<?= esc($customer['id']) ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/dashboard/admin/pelanggan/<?= esc($customer['id']) ?>/update" method="post" class="modal-content dashboard-modal">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" value="<?= esc($customer['nama']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= esc($customer['no_hp']) ?>">
                    </div>
                    <div class="form-group mb-0">
                        <label>Hubungkan Akun User (Opsional)</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Tidak Dihubungkan / Offline --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= esc($user['id']) ?>" <?= $customer['user_id'] == $user['id'] ? 'selected' : '' ?>>
                                    <?= esc($user['nama']) ?> (<?= esc($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content dashboard-modal">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="confirmBody">
                Apakah Anda yakin ingin melakukan tindakan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning btn-sm" id="confirmSubmitBtn">Yakin</button>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    var activeFormId = null;

    $('.trigger-confirm').click(function() {
        activeFormId = $(this).attr('data-form-id');
        var message = $(this).attr('data-message');
        $('#confirmBody').text(message);
        $('#confirmActionModal').modal('show');
    });

    $('#confirmSubmitBtn').click(function() {
        if (activeFormId) {
            $('#' + activeFormId).submit();
        }
    });
});
</script>
</body>
</html>
