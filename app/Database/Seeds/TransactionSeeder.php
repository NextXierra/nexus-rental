<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $reservasiBuilder = $db->table('reservasi');
        $pembayaranBuilder = $db->table('pembayaran');

        // Check if database already has transactions to avoid duplicate seeding
        if ($reservasiBuilder->countAllResults() > 0) {
            return;
        }

        $pelangganList = $db->table('pelanggan')->get()->getResultArray();
        $unitList = $db->table('unit_ps')->get()->getResultArray();

        if (empty($pelangganList) || empty($unitList)) {
            return;
        }

        $now = time();
        $metodeList = ['tunai', 'qris'];

        // Generate 30 mock reservations
        for ($i = 0; $i < 30; $i++) {
            $pelanggan = $pelangganList[array_rand($pelangganList)];
            $unit = $unitList[array_rand($unitList)];

            // Random start time within last 14 days
            // E.g., rand(0, 14) days ago, and random hour between 9:00 and 22:00
            $daysAgo = rand(0, 14);
            $startHour = rand(9, 21);
            $startMinute = array_rand([0 => 0, 1 => 30]); // Start on hour or half hour
            
            $waktuMulaiTs = strtotime("-$daysAgo days 00:00:00") + ($startHour * 3600) + ($startMinute * 60);
            $totalJam = rand(1, 5);
            $waktuSelesaiTs = $waktuMulaiTs + ($totalJam * 3600);

            $waktuMulaiStr = date('Y-m-d H:i:s', $waktuMulaiTs);
            $waktuSelesaiStr = date('Y-m-d H:i:s', $waktuSelesaiTs);

            // Determine status
            if ($waktuSelesaiTs < $now) {
                // Past reservations
                $status = (rand(1, 10) <= 9) ? 'selesai' : 'dibatalkan';
            } elseif ($waktuMulaiTs <= $now && $waktuSelesaiTs >= $now) {
                // Active reservations
                $status = 'aktif';
            } else {
                // Future reservations
                $status = (rand(1, 10) <= 7) ? 'aktif' : 'pending';
            }

            $tipe = ($pelanggan['user_id'] !== null) ? 'online' : 'offline';
            $hargaPerJam = $unit['harga_per_jam'];
            $totalHarga = $hargaPerJam * $totalJam;

            $reservationData = [
                'pelanggan_id'  => $pelanggan['id'],
                'unit_id'       => $unit['id'],
                'tipe'          => $tipe,
                'waktu_mulai'   => $waktuMulaiStr,
                'waktu_selesai' => $waktuSelesaiStr,
                'total_jam'     => $totalJam,
                'harga_per_jam' => $hargaPerJam,
                'total_harga'   => $totalHarga,
                'status'        => $status,
                'created_at'    => date('Y-m-d H:i:s', $waktuMulaiTs - rand(600, 3600)) // created slightly before
            ];

            $reservasiBuilder->insert($reservationData);
            $reservasiId = $db->insertID();

            // Generate payment
            $metode = $metodeList[array_rand($metodeList)];
            
            if ($status === 'selesai') {
                $pembayaranStatus = 'lunas';
            } elseif ($status === 'dibatalkan') {
                $pembayaranStatus = (rand(1, 2) === 1) ? 'belum_bayar' : 'sudah_bayar'; // If online cancellation, might be paid or unpaid
            } elseif ($status === 'aktif') {
                $pembayaranStatus = (rand(1, 10) <= 9) ? 'lunas' : 'belum_bayar';
            } else { // pending
                $pembayaranStatus = (rand(1, 10) <= 5) ? 'belum_bayar' : 'sudah_bayar';
            }

            $pembayaranData = [
                'reservasi_id' => $reservasiId,
                'jumlah'       => $totalHarga,
                'metode'       => $metode,
                'status'       => $pembayaranStatus,
                'dibayar_at'   => $waktuMulaiStr
            ];

            $pembayaranBuilder->insert($pembayaranData);
        }
    }
}
