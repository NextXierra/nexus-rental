<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi Saya - Nexus Rental</title>
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css?v=6">
</head>
<body class="dashboard-body user-dashboard">
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a href="/" class="dashboard-brand">Nexus Rental</a>
        <nav class="dashboard-nav">
            <a href="/dashboard/user"><i class="fa fa-home"></i> Home</a>
            <a href="/dashboard/user/reservasi" class="active"><i class="fa fa-calendar-check-o"></i> Reservasi Saya</a>
            <a href="/dashboard/user/profil"><i class="fa fa-user"></i> Profil</a>
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
                <span><?= esc(session()->get('nama') ?? 'User') ?></span>
                <a href="/dashboard/user/profil" class="dashboard-profile" aria-label="Profil"><i class="fa fa-user"></i></a>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="dashboard-page-title">Reservasi Saya</h1>
                    <p class="dashboard-page-subtitle">Daftar riwayat booking Playstation Anda.</p>
                </div>
                <button class="dashboard-button" type="button" data-toggle="modal" data-target="#createBookingModal">
                    <i class="fa fa-plus"></i> Ajukan Booking
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
                                <th>Unit PS</th>
                                <th>Tipe</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Total Jam</th>
                                <th>Total Harga</th>
                                <th>Status Reservasi</th>
                                <th>Metode Bayar</th>
                                <th>Status Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reservations): ?>
                                <?php foreach ($reservations as $res): ?>
                                    <tr>
                                        <td><?= esc($res['nama_unit']) ?></td>
                                        <td><span class="badge badge-secondary"><?= esc($res['tipe']) ?></span></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_mulai'])) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($res['waktu_selesai'])) ?></td>
                                        <td><?= esc($res['total_jam']) ?> Jam</td>
                                        <td>Rp <?= number_format((int) $res['total_harga'], 0, ',', '.') ?></td>
                                        <td><span class="status-pill <?= esc($res['status']) ?>"><?= esc($res['status']) ?></span></td>
                                        <td><span class="badge badge-warning text-dark"><?= esc(strtoupper($res['metode_bayar'] ?? '-')) ?></span></td>
                                        <td><span class="status-pill <?= esc($res['status_bayar'] ?? 'belum_bayar') ?>"><?= esc($res['status_bayar'] ?? 'belum_bayar') ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Anda belum memiliki riwayat reservasi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="modal fade" id="createBookingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/dashboard/user/reservasi/store" method="post" class="modal-content dashboard-modal">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Ajukan Booking PS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Live AJAX Overlap Alert -->
                <div class="alert alert-danger d-none" id="modal_overlap_alert"></div>

                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label>Tanggal Booking</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= esc(old('tanggal_mulai', date('Y-m-d'))) ?>" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Jam Mulai</label>
                        <div class="d-flex align-items-center">
                            <select name="jam_mulai_hour" class="form-control mr-1" required>
                                <?php for($i=0; $i<24; $i++): $h = sprintf("%02d", $i); ?>
                                    <option value="<?= $h ?>" <?= date('H') == $h ? 'selected' : '' ?>><?= $h ?></option>
                                <?php endfor; ?>
                            </select>
                            <span class="mx-1">:</span>
                            <select name="jam_mulai_minute" class="form-control ml-1" required>
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
                <button type="submit" class="btn btn-warning">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/popper/popper.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    function checkAvailability() {
        var unitId = $('select[name="unit_id"]').val();
        var tanggalMulai = $('input[name="tanggal_mulai"]').val();
        var jamMulaiHour = $('select[name="jam_mulai_hour"]').val();
        var jamMulaiMinute = $('select[name="jam_mulai_minute"]').val();
        var waktuMulai = tanggalMulai + ' ' + jamMulaiHour + ':' + jamMulaiMinute;
        var durasi = $('input[name="durasi"]').val();

        // Bersihkan state awal
        $('#modal_overlap_alert').addClass('d-none').text('');
        $('#createBookingModal button[type="submit"]').removeAttr('disabled');

        if (!unitId || !durasi || !tanggalMulai || !jamMulaiHour || !jamMulaiMinute) {
            return;
        }

        var url = '/dashboard/admin/reservasi/check-availability?unit_id=' + unitId + '&tipe=online&durasi=' + durasi + '&waktu_mulai=' + encodeURIComponent(waktuMulai);

        fetch(url)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.available === false) {
                    $('#modal_overlap_alert').removeClass('d-none').text(data.message);
                    $('#createBookingModal button[type="submit"]').attr('disabled', 'disabled');
                }
            })
            .catch(function(err) {
                console.error('Gagal mengecek ketersediaan unit', err);
            });
    }

    function updateUnitOptions() {
        var tanggalMulai = $('input[name="tanggal_mulai"]').val();
        var jamMulaiHour = $('select[name="jam_mulai_hour"]').val();
        var jamMulaiMinute = $('select[name="jam_mulai_minute"]').val();
        var waktuMulai = tanggalMulai + ' ' + jamMulaiHour + ':' + jamMulaiMinute;
        var durasi = $('input[name="durasi"]').val();

        if (!durasi || !tanggalMulai || !jamMulaiHour || !jamMulaiMinute) {
            return;
        }

        var url = '/dashboard/admin/reservasi/check-units?tipe=online&durasi=' + durasi + '&waktu_mulai=' + encodeURIComponent(waktuMulai);
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

                    // Trigger checkAvailability
                    checkAvailability();
                }
            })
            .catch(function(err) {
                console.error('Gagal memuat unit PS', err);
            });
    }

    // Bind event check
    $('select[name="unit_id"]').on('change', checkAvailability);
    $('input[name="tanggal_mulai"], select[name="jam_mulai_hour"], select[name="jam_mulai_minute"], input[name="durasi"]').on('change keyup', function() {
        updateUnitOptions();
    });

    updateUnitOptions();

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('book') || <?= (session()->getFlashdata('errors') || session()->getFlashdata('error')) ? 'true' : 'false' ?>) {
        $('#createBookingModal').modal('show');
    }
});
</script>
</body>
</html>
