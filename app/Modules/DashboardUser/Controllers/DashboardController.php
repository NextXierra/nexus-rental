<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $pelanggan = $db->table('pelanggan')
            ->where('user_id', $userId)
            ->get()->getRowArray();
        $pelangganId = $pelanggan ? $pelanggan['id'] : null;

        $totalReservations = 0;
        $activeReservationsCount = 0;
        $totalSpent = 0;
        $myReservationsList = [];

        if ($pelangganId) {
            $totalReservations = $db->table('reservasi')
                ->where('pelanggan_id', $pelangganId)
                ->countAllResults();

            $activeReservationsCount = $db->table('reservasi')
                ->where('pelanggan_id', $pelangganId)
                ->whereIn('status', ['pending', 'aktif'])
                ->countAllResults();

            $totalSpent = $db->table('pembayaran')
                ->join('reservasi', 'pembayaran.reservasi_id = reservasi.id')
                ->where('reservasi.pelanggan_id', $pelangganId)
                ->where('pembayaran.status', 'lunas')
                ->selectSum('pembayaran.jumlah')
                ->get()->getRowArray()['jumlah'] ?? 0;

            $myReservationsList = $db->table('reservasi')
                ->select('reservasi.*, unit_ps.nama_unit, unit_ps.tipe as tipe_unit, pembayaran.metode as metode_bayar, pembayaran.status as status_bayar')
                ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
                ->join('pembayaran', 'reservasi.id = pembayaran.reservasi_id', 'left')
                ->where('reservasi.pelanggan_id', $pelangganId)
                ->orderBy('reservasi.created_at', 'DESC')
                ->limit(3)
                ->get()->getResultArray();
        }

        $now = date('Y-m-d H:i:s');
        $activeBookedUnitIds = $db->table('reservasi')
            ->select('unit_id')
            ->where('status', 'aktif')
            ->where('waktu_mulai <=', $now)
            ->where('waktu_selesai >=', $now)
            ->get()->getResultArray();
        $bookedIds = array_column($activeBookedUnitIds, 'unit_id');

        $units = $db->table('unit_ps')
            ->whereIn('status', ['tersedia', 'disewa'])
            ->orderBy('tipe', 'ASC')
            ->orderBy('nama_unit', 'ASC')
            ->get()->getResultArray();

        $availableUnitsList = [];
        foreach ($units as $unit) {
            $unit['is_booked'] = in_array($unit['id'], $bookedIds) || ($unit['status'] === 'disewa');
            $availableUnitsList[] = $unit;
        }

        return view('Modules\DashboardUser\Views\dashboard', [
            'totalReservations'       => $totalReservations,
            'activeReservationsCount' => $activeReservationsCount,
            'totalSpent'              => (int) $totalSpent,
            'myReservationsList'      => $myReservationsList,
            'availableUnitsList'      => $availableUnitsList,
        ]);
    }
}
