<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Pendapatan - Nexus Rental</title>
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
        }
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .print-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .print-header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .metric-box {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .metric-box span {
            font-size: 11px;
            display: block;
            font-weight: bold;
        }
        .metric-box strong {
            font-size: 18px;
            display: block;
            margin-top: 5px;
        }
        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-print th, .table-print td {
            border: 2px solid #000;
            padding: 8px 12px;
            font-size: 12px;
        }
        .table-print th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-section {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 80px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row no-print mb-4">
        <div class="col-12 text-right">
            <button onclick="window.print();" class="btn btn-primary">Cetak Sekarang</button>
            <button onclick="window.close();" class="btn btn-secondary">Tutup Halaman</button>
        </div>
    </div>

    <div class="print-header">
        <h1>Nexus Rental</h1>
        <p>Laporan Pendapatan Playstation</p>
        <p class="font-weight-bold">Periode: <?= esc($label) ?> (Tipe: <?= esc(ucfirst($filter)) ?>)</p>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="metric-box">
                <span>TOTAL PENDAPATAN</span>
                <strong>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong>
            </div>
        </div>
        <div class="col-3">
            <div class="metric-box">
                <span>TOTAL TRANSAKSI</span>
                <strong><?= $total_transaksi ?></strong>
            </div>
        </div>
        <div class="col-3">
            <div class="metric-box">
                <span>METODE TUNAI</span>
                <strong><?= $tunai_count ?></strong>
            </div>
        </div>
        <div class="col-3">
            <div class="metric-box">
                <span>METODE QRIS</span>
                <strong><?= $qris_count ?></strong>
            </div>
        </div>
    </div>

    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Waktu Pembayaran</th>
                <th>Pelanggan</th>
                <th>Unit PS</th>
                <th>Tipe Sewa</th>
                <th>Durasi</th>
                <th>Metode</th>
                <th>Status</th>
                <th style="text-align: right; width: 15%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments): ?>
                <?php $no = 1; foreach ($payments as $pay): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($pay['dibayar_at'])) ?></td>
                        <td><?= esc($pay['nama_pelanggan']) ?></td>
                        <td><?= esc($pay['nama_unit']) ?></td>
                        <td><?= esc(strtoupper($pay['tipe_sewa'])) ?></td>
                        <td><?= esc($pay['total_jam']) ?> Jam</td>
                        <td><?= esc(strtoupper($pay['metode'])) ?></td>
                        <td><?= esc(strtoupper($pay['status'])) ?></td>
                        <td style="text-align: right;">Rp <?= number_format((int) $pay['jumlah'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Belum ada data pendapatan untuk periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <p>Banjarbaru, <?= date('d M Y') ?></p>
        <p>Petugas Admin,</p>
        <div class="signature-space"></div>
        <p class="font-weight-bold" style="border-bottom: 1px solid #000; display: inline-block;">Nexus Rental Admin</p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>