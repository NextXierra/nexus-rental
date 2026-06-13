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

                <div class="form-group">
                    <label>Status Pelanggan</label>
                    <select id="status_pelanggan" name="status_pelanggan" class="form-control" required>
                        <option value="user">Member</option>
                        <option value="pelanggan">Non-Member</option>
                    </select>
                </div>

                <!-- Block User Terdaftar (Member) -->
                <div class="form-group" id="block_user">
                    <label>Pilih Member</label>
                    <select name="user_id" class="form-control">
                        <option value="">-- Pilih Member --</option>
                        <?php foreach ($userList as $u): ?>
                            <option value="<?= esc($u['id']) ?>" <?= old('user_id') == $u['id'] ? 'selected' : '' ?>><?= esc($u['nama']) ?> (<?= esc($u['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Block Pelanggan Biasa -->
                <div class="d-none" id="block_pelanggan">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" value="<?= esc(old('nama')) ?>">
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipe Layanan</label>
                    <select id="tipe_layanan" name="tipe" class="form-control" required>
                        <option value="offline">Main Langsung</option>
                        <option value="online">Booking</option>
                    </select>
                </div>
                <div class="form-group d-none" id="block_waktu_mulai">
                    <label>Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control" value="<?= esc(old('waktu_mulai')) ?>">
                </div>
                <div class="form-group">
                    <label>Durasi (Jam)</label>
                    <input type="number" name="durasi" class="form-control" value="<?= esc(old('durasi', 1)) ?>" min="1" max="24" required>
                </div>

                <div class="form-group">
                    <label>Pilih Unit PS</label>
                    <select name="unit_id" class="form-control" required>
                        <option value="">-- Pilih Unit --</option>
                        <?php foreach ($unitList as $u): ?>
                            <option value="<?= esc($u['id']) ?>" <?= old('unit_id') == $u['id'] ? 'selected' : '' ?>><?= esc($u['nama_unit']) ?> (Rp <?= number_format((int) $u['harga_per_jam'], 0, ',', '.') ?>/jam) <?= $u['status'] !== 'tersedia' ? '[' . strtoupper(esc($u['status'])) . ']' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Metode Pembayaran</label>
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
<script>
$(document).ready(function() {
    $('#status_pelanggan').change(function() {
        var status = $(this).val();
        $('#block_user').addClass('d-none');
        $('#block_pelanggan').addClass('d-none');
        
        $('#block_user select').removeAttr('required');
        $('#block_pelanggan input').removeAttr('required');

        if (status === 'user') {
            $('#block_user').removeClass('d-none');
            $('#block_user select').attr('required', 'required');
        } else if (status === 'pelanggan') {
            $('#block_pelanggan').removeClass('d-none');
            $('input[name="nama"]').attr('required', 'required');
        }
    });

    $('#tipe_layanan').change(function() {
        var tipe = $(this).val();
        if (tipe === 'offline') {
            $('#block_waktu_mulai').addClass('d-none');
            $('input[name="waktu_mulai"]').removeAttr('required');
        } else {
            $('#block_waktu_mulai').removeClass('d-none');
            $('input[name="waktu_mulai"]').attr('required', 'required');
        }
    });

    // trigger initial states
    $('#status_pelanggan').trigger('change');
    $('#tipe_layanan').trigger('change');

    <?php if (session()->getFlashdata('errors') || session()->getFlashdata('error')): ?>
        $('#createReservasiModal').modal('show');
    <?php endif; ?>
});
</script>
</body>
</html>
