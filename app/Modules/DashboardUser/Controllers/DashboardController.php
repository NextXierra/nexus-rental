<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // 1. Get Pelanggan ID
        $pelanggan = $db->table('pelanggan')
            ->where('user_id', $userId)
            ->get()->getRowArray();
        $pelangganId = $pelanggan ? $pelanggan['id'] : null;

        // 2. Metrics & Lists defaults
        $totalReservations = 0;
        $activeReservationsCount = 0;
        $totalSpent = 0;
        $myReservationsList = [];

        if ($pelangganId) {
            // Total Reservations Count
            $totalReservations = $db->table('reservasi')
                ->where('pelanggan_id', $pelangganId)
                ->countAllResults();

            // Active or Pending Reservations Count
            $activeReservationsCount = $db->table('reservasi')
                ->where('pelanggan_id', $pelangganId)
                ->whereIn('status', ['pending', 'aktif'])
                ->countAllResults();

            // Total spent (lunas payments)
            $totalSpent = $db->table('pembayaran')
                ->join('reservasi', 'pembayaran.reservasi_id = reservasi.id')
                ->where('reservasi.pelanggan_id', $pelangganId)
                ->where('pembayaran.status', 'lunas')
                ->selectSum('pembayaran.jumlah')
                ->get()->getRowArray()['jumlah'] ?? 0;

            // Get recent reservations (limit 3)
            $myReservationsList = $db->table('reservasi')
                ->select('reservasi.*, unit_ps.nama_unit, unit_ps.tipe as tipe_unit, pembayaran.metode as metode_bayar, pembayaran.status as status_bayar')
                ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
                ->join('pembayaran', 'reservasi.id = pembayaran.reservasi_id', 'left')
                ->where('reservasi.pelanggan_id', $pelangganId)
                ->orderBy('reservasi.created_at', 'DESC')
                ->limit(3)
                ->get()->getResultArray();
        }

        // 3. Get Available Units Catalog
        $availableUnitsList = $db->table('unit_ps')
            ->where('status', 'tersedia')
            ->orderBy('tipe', 'ASC')
            ->orderBy('nama_unit', 'ASC')
            ->get()->getResultArray();

        return view('Modules\DashboardUser\Views\dashboard', [
            'totalReservations'       => $totalReservations,
            'activeReservationsCount' => $activeReservationsCount,
            'totalSpent'              => (int) $totalSpent,
            'myReservationsList'      => $myReservationsList,
            'availableUnitsList'      => $availableUnitsList,
        ]);
    }
}
