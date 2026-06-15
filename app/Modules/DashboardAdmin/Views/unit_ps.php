<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Unit PS - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
                                <form id="delete-form-<?= esc($unit['id']) ?>" action="/dashboard/admin/unit-ps/<?= esc($unit['id']) ?>/delete" method="post" class="d-none">
                                    <?= csrf_field() ?>
                                </form>
                                <button class="btn btn-sm btn-outline-danger trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menghapus unit PS ini?" data-form-id="delete-form-<?= esc($unit['id']) ?>">Hapus</button>
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
    <?php if (isset($pager)): ?>
        <div class="d-flex justify-content-center pt-3 pb-3">
            <?= $pager->links('units', 'brutal') ?>
        </div>
    <?php endif; ?>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
<?= $this->endSection() ?>
