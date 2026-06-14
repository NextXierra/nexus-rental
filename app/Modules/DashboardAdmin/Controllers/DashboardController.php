<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $db = \Config\Database::connect();

        // 1. Today's Revenue
        $todayRevenue = $db->table('pembayaran')
            ->where('status', 'lunas')
            ->where('DATE(dibayar_at)', date('Y-m-d'))
            ->selectSum('jumlah')
            ->get()->getRowArray()['jumlah'] ?? 0;

        // 2. Active Reservations Count
        $activeReservations = $db->table('reservasi')
            ->where('status', 'aktif')
            ->countAllResults();

        // 3. Available Units Count
        $availableUnits = $db->table('unit_ps')
            ->where('status', 'tersedia')
            ->countAllResults();
        $totalUnits = $db->table('unit_ps')
            ->countAllResults();

        // 4. Total Customers
        $totalCustomers = $db->table('pelanggan')
            ->countAllResults();

        // 5. Recent Active Reservations List
        $activeReservationsList = $db->table('reservasi')
            ->select('reservasi.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->where('reservasi.status', 'aktif')
            ->orderBy('reservasi.waktu_mulai', 'ASC')
            ->limit(5)
            ->get()->getResultArray();

        // 6. Weekly Revenue (Last 7 Days)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayLabel = date('D', strtotime($date));
            $dateLabel = date('d M', strtotime($date));
            
            $daysIndo = [
                'Sun' => 'Min',
                'Mon' => 'Sen',
                'Tue' => 'Sel',
                'Wed' => 'Rab',
                'Thu' => 'Kam',
                'Fri' => 'Jum',
                'Sat' => 'Sab'
            ];
            $dayLabelIndo = $daysIndo[$dayLabel] ?? $dayLabel;

            $weeklyData[$date] = [
                'day' => $dayLabelIndo,
                'date' => $dateLabel,
                'amount' => 0,
            ];
        }

        $weeklyRevenue = $db->table('pembayaran')
            ->select('DATE(dibayar_at) as tanggal, SUM(jumlah) as total')
            ->where('status', 'lunas')
            ->where('DATE(dibayar_at) >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('DATE(dibayar_at)')
            ->get()->getResultArray();

        foreach ($weeklyRevenue as $row) {
            $dateKey = $row['tanggal'];
            if (isset($weeklyData[$dateKey])) {
                $weeklyData[$dateKey]['amount'] = (int) $row['total'];
            }
        }

        return view('Modules\DashboardAdmin\Views\dashboard', [
            'todayRevenue' => (int) $todayRevenue,
            'activeReservations' => $activeReservations,
            'availableUnits' => $availableUnits,
            'totalUnits' => $totalUnits,
            'totalCustomers' => $totalCustomers,
            'weeklyData' => array_values($weeklyData),
            'activeReservationsList' => $activeReservationsList,
        ]);
    }
}
