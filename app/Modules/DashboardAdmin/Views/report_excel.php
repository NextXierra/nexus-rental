<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        .header {
            background-color: #F2F2F2;
            font-weight: bold;
            border: 1px solid #000;
        }
        .border-all {
            border: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="9" class="title">LAPORAN PENDAPATAN - NEXUS RENTAL</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">PERIODE: <?= esc(strtoupper($label)) ?></td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">TIPE LAPORAN: <?= esc(strtoupper($filter)) ?></td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="3" class="bold border-all" style="background-color: #E2EFDA;">Total Pendapatan</td>
            <td colspan="6" class="bold border-all text-right" style="background-color: #E2EFDA;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="3" class="bold border-all" style="background-color: #FFF2CC;">Total Transaksi Lunas</td>
            <td colspan="6" class="bold border-all text-right" style="background-color: #FFF2CC;"><?= $total_transaksi ?> Transaksi</td>
        </tr>
        <tr>
            <td colspan="3" class="bold border-all">Metode Tunai</td>
            <td colspan="6" class="bold border-all text-right"><?= $tunai_count ?> Transaksi</td>
        </tr>
        <tr>
            <td colspan="3" class="bold border-all">Metode QRIS</td>
            <td colspan="6" class="bold border-all text-right"><?= $qris_count ?> Transaksi</td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>
        <thead>
            <tr>
                <th class="header border-all" style="width: 50px;">No</th>
                <th class="header border-all">Waktu Pembayaran</th>
                <th class="header border-all">Pelanggan</th>
                <th class="header border-all">Unit PS</th>
                <th class="header border-all">Tipe Sewa</th>
                <th class="header border-all">Durasi</th>
                <th class="header border-all">Metode</th>
                <th class="header border-all">Status</th>
                <th class="header border-all text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments): ?>
                <?php $no = 1; foreach ($payments as $pay): ?>
                    <tr>
                        <td class="border-all text-center"><?= $no++ ?></td>
                        <td class="border-all"><?= date('d-m-Y H:i', strtotime($pay['dibayar_at'])) ?></td>
                        <td class="border-all"><?= esc($pay['nama_pelanggan']) ?></td>
                        <td class="border-all"><?= esc($pay['nama_unit']) ?></td>
                        <td class="border-all text-center"><?= esc(strtoupper($pay['tipe_sewa'])) ?></td>
                        <td class="border-all text-center"><?= esc($pay['total_jam']) ?> Jam</td>
                        <td class="border-all text-center"><?= esc(strtoupper($pay['metode'])) ?></td>
                        <td class="border-all text-center"><?= esc(strtoupper($pay['status'])) ?></td>
                        <td class="border-all text-right">Rp <?= number_format((int) $pay['jumlah'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="bold">
                    <td colspan="8" class="border-all text-right" style="background-color: #F2F2F2;">GRAND TOTAL</td>
                    <td class="border-all text-right" style="background-color: #F2F2F2;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="border-all text-center">Belum ada data pendapatan untuk periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>