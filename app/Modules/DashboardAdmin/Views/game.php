<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Kelola Games - Nexus Rental<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <div>
        <h1 class="dashboard-page-title">Games</h1>
        <p class="dashboard-page-subtitle">Kelola daftar game Playstation rental.</p>
    </div>
    <button class="dashboard-button" type="button" data-toggle="modal" data-target="#createGameModal">
        <i class="fa fa-plus"></i> Tambah Game
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
                    <th style="width: 100px;">Cover</th>
                    <th>Nama Game</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($games): ?>
                    <?php foreach ($games as $game): ?>
                        <tr>
                            <td>
                                <img src="/images/<?= esc($game['gambar']) ?>" alt="<?= esc($game['nama_game']) ?>" class="img-thumbnail" style="max-height: 80px; max-width: 80px; object-fit: cover; border: var(--border-rough);">
                            </td>
                            <td class="align-middle" style="font-size: 16px; font-weight: bold;"><?= esc($game['nama_game']) ?></td>
                            <td class="text-right align-middle">
                                <button class="btn btn-sm btn-outline-warning" type="button" data-toggle="modal" data-target="#editGameModal<?= esc($game['id']) ?>">Edit</button>
                                <form id="delete-form-<?= esc($game['id']) ?>" action="/dashboard/admin/games/<?= esc($game['id']) ?>/delete" method="post" class="d-none">
                                    <?= csrf_field() ?>
                                </form>
                                <button class="btn btn-sm btn-outline-danger trigger-confirm" type="button" data-message="Apakah Anda yakin ingin menghapus game ini?" data-form-id="delete-form-<?= esc($game['id']) ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada game Playstation.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Game -->
<div class="modal fade" id="createGameModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/dashboard/admin/games/store" method="post" enctype="multipart/form-data" class="modal-content dashboard-modal">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Game</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Game</label>
                    <input type="text" name="nama_game" class="form-control" value="<?= esc(old('nama_game')) ?>" required>
                </div>
                <div class="form-group mb-0">
                    <label>Cover Game (Image)</label>
                    <input type="file" name="gambar" class="form-control-file" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Game -->
<?php foreach ($games as $game): ?>
    <div class="modal fade" id="editGameModal<?= esc($game['id']) ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/dashboard/admin/games/<?= esc($game['id']) ?>/update" method="post" enctype="multipart/form-data" class="modal-content dashboard-modal">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Game</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Game</label>
                        <input type="text" name="nama_game" class="form-control" value="<?= esc($game['nama_game']) ?>" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Cover Game (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="gambar" class="form-control-file">
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
