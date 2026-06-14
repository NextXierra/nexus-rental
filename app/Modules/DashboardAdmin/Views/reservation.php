<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi - Nexus Rental</title>
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
            <a href="/dashboard/admin/reservasi" class="active"><i class="fa fa-calendar-check-o"></i> Reservasi</a>
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

            <!-- Tabel Permintaan Reservasi -->
            <div class="dashboard-panel mb-4">
                <div class="panel-heading d-flex align-items-center justify-content-between py-3 px-4">
                    <h2 class="mb-0" style="font-size: 16px;">Permintaan Reservasi</h2>
                    <span class="badge badge-warning text-dark"><?= count($pendingReservations) ?> Permintaan</span>
                </div>
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
                                <th class="text-right">Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($pendingReservations): ?>
                                <?php foreach ($pendingReservations as $res): ?>
                                    <tr>
                                        <td><?= esc($res['nama_pelanggan']) ?></td>
                                        <td><?= esc($res['nama_unit']) ?></td>
                                        <td><span class="badge badge-secondary"><?= esc($res['tipe']) ?></span></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_mulai'])) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_selesai'])) ?></td>
                                        <td><?= esc($res['total_jam']) ?> Jam</td>
                                        <td>Rp <?= number_format((int) $res['total_harga'], 0, ',', '.') ?></td>
                                        <td class="text-right">
                                            <form id="approve-form-<?= esc($res['id']) ?>" action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/approve" method="post" class="d-none">
                                                <?= csrf_field() ?>
                                            </form>
                                            <button class="btn btn-sm btn-outline-success trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menyetujui reservasi ini?" data-form-id="approve-form-<?= esc($res['id']) ?>">Setujui</button>

                                            <form id="reject-form-<?= esc($res['id']) ?>" action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/reject" method="post" class="d-none">
                                                <?= csrf_field() ?>
                                            </form>
                                            <button class="btn btn-sm btn-outline-danger trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menolak permintaan reservasi ini?" data-form-id="reject-form-<?= esc($res['id']) ?>">Tolak</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Tidak ada permintaan reservasi masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-panel">
                <div class="panel-heading py-3 px-4">
                    <h2 class="mb-0" style="font-size: 16px;">Daftar Reservasi</h2>
                </div>
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
                                                <form id="complete-form-<?= esc($res['id']) ?>" action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/complete" method="post" class="d-none">
                                                    <?= csrf_field() ?>
                                                </form>
                                                <button class="btn btn-sm btn-outline-success trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menyelesaikan reservasi ini?" data-form-id="complete-form-<?= esc($res['id']) ?>">Selesai</button>

                                                <form id="cancel-form-<?= esc($res['id']) ?>" action="/dashboard/admin/reservasi/<?= esc($res['id']) ?>/cancel" method="post" class="d-none">
                                                    <?= csrf_field() ?>
                                                </form>
                                                <button class="btn btn-sm btn-outline-danger trigger-confirm" type="button" data-message="Apakah Anda yakin ingin membatalkan reservasi ini?" data-form-id="cancel-form-<?= esc($res['id']) ?>">Batal</button>
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

                <!-- Live AJAX Overlap Alert -->
                <div class="alert alert-danger d-none" id="modal_overlap_alert"></div>

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
                <div class="form-row d-none" id="block_waktu_mulai">
                    <div class="form-group col-sm-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= esc(old('tanggal_mulai', date('Y-m-d'))) ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Jam Mulai</label>
                        <div class="d-flex align-items-center">
                            <select name="jam_mulai_hour" class="form-control mr-1">
                                <?php for($i=0; $i<24; $i++): $h = sprintf("%02d", $i); ?>
                                    <option value="<?= $h ?>" <?= date('H') == $h ? 'selected' : '' ?>><?= $h ?></option>
                                <?php endfor; ?>
                            </select>
                            <span class="mx-1">:</span>
                            <select name="jam_mulai_minute" class="form-control ml-1">
                                <?php for($i=0; $i<60; $i+=5): $m = sprintf("%02d", $i); ?>
                                    <option value="<?= $m ?>" <?= (floor(date('i')/5)*5) == $i ? 'selected' : '' ?>><?= $m ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
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
            $('input[name="tanggal_mulai"]').removeAttr('required');
            $('select[name="jam_mulai_hour"]').removeAttr('required');
            $('select[name="jam_mulai_minute"]').removeAttr('required');
        } else {
            $('#block_waktu_mulai').removeClass('d-none');
            $('input[name="tanggal_mulai"]').attr('required', 'required');
            $('select[name="jam_mulai_hour"]').attr('required', 'required');
            $('select[name="jam_mulai_minute"]').attr('required', 'required');
        }
    });

    function checkAvailability() {
        var unitId = $('select[name="unit_id"]').val();
        var tipe = $('#tipe_layanan').val();
        var tanggalMulai = $('input[name="tanggal_mulai"]').val();
        var jamMulaiHour = $('select[name="jam_mulai_hour"]').val();
        var jamMulaiMinute = $('select[name="jam_mulai_minute"]').val();
        var waktuMulai = tanggalMulai + ' ' + jamMulaiHour + ':' + jamMulaiMinute;
        var durasi = $('input[name="durasi"]').val();

        // Bersihkan state awal
        $('#modal_overlap_alert').addClass('d-none').text('');
        $('#createReservasiModal button[type="submit"]').removeAttr('disabled');

        if (!unitId || !tipe || !durasi) {
            return;
        }

        if (tipe === 'online' && (!tanggalMulai || !jamMulaiHour || !jamMulaiMinute)) {
            return;
        }

        var url = '/dashboard/admin/reservasi/check-availability?unit_id=' + unitId + '&tipe=' + tipe + '&durasi=' + durasi;
        if (tipe === 'online') {
            url += '&waktu_mulai=' + encodeURIComponent(waktuMulai);
        }

        fetch(url)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.available === false) {
                    $('#modal_overlap_alert').removeClass('d-none').text(data.message);
                    $('#createReservasiModal button[type="submit"]').attr('disabled', 'disabled');
                }
            })
            .catch(function(err) {
                console.error('Gagal mengecek ketersediaan unit', err);
            });
    }

    function updateUnitOptions() {
        var tipe = $('#tipe_layanan').val();
        var tanggalMulai = $('input[name="tanggal_mulai"]').val();
        var jamMulaiHour = $('select[name="jam_mulai_hour"]').val();
        var jamMulaiMinute = $('select[name="jam_mulai_minute"]').val();
        var waktuMulai = tanggalMulai + ' ' + jamMulaiHour + ':' + jamMulaiMinute;
        var durasi = $('input[name="durasi"]').val();

        if (!tipe || !durasi) {
            return;
        }

        if (tipe === 'online' && (!tanggalMulai || !jamMulaiHour || !jamMulaiMinute)) {
            return;
        }

        var url = '/dashboard/admin/reservasi/check-units?tipe=' + tipe + '&durasi=' + durasi;
        if (tipe === 'online') {
            url += '&waktu_mulai=' + encodeURIComponent(waktuMulai);
        }

        var currentSelectedUnit = $('select[name="unit_id"]').val();

        fetch(url)
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                if (data.status === 'success') {
                    var selectUnit = $('select[name="unit_id"]');
                    selectUnit.empty();
                    selectUnit.append('<option value="">-- Pilih Unit --</option>');

                    data.units.forEach(function(u) {
                        var optionText = u.nama_unit + ' (Rp ' + parseInt(u.harga_per_jam).toLocaleString('id-ID') + '/jam)';
                        var disabledAttr = '';
                        
                        if (u.is_booked) {
                            optionText += ' [DISEWA]';
                            disabledAttr = ' disabled="disabled"';
                        }

                        var selectedAttr = '';
                        if (u.id == currentSelectedUnit && !u.is_booked) {
                            selectedAttr = ' selected="selected"';
                        }

                        selectUnit.append('<option value="' + u.id + '"' + disabledAttr + selectedAttr + '>' + optionText + '</option>');
                    });

                    // Trigger checkAvailability to re-validate selected unit
                    checkAvailability();
                }
            })
            .catch(function(err) {
                console.error('Gagal memuat unit PS', err);
            });
    }

    // Bind event check
    $('select[name="unit_id"]').on('change', checkAvailability);
    $('#tipe_layanan, input[name="tanggal_mulai"], select[name="jam_mulai_hour"], select[name="jam_mulai_minute"], input[name="durasi"]').on('change keyup', function() {
        updateUnitOptions();
    });

    // trigger initial states
    $('#status_pelanggan').trigger('change');
    $('#tipe_layanan').trigger('change');
    updateUnitOptions();

    <?php if (session()->getFlashdata('errors') || session()->getFlashdata('error')): ?>
        $('#createReservasiModal').modal('show');
    <?php endif; ?>
});
</script>
</body>
</html>
