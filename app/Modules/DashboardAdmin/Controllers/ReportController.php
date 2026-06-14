<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;

class ReportController extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $filter = $this->request->getGet('filter') ?? 'harian';
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $minggu = $this->request->getGet('minggu') ?? date('Y-m-d');

        $data = $this->getReportData($filter, $tanggal, $minggu, $bulan);

        return view('Modules\DashboardAdmin\Views\report', $data);
    }

    public function printLaporan()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $filter = $this->request->getGet('filter') ?? 'harian';
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $minggu = $this->request->getGet('minggu') ?? date('Y-m-d');

        $data = $this->getReportData($filter, $tanggal, $minggu, $bulan);

        return view('Modules\DashboardAdmin\Views\report_print', $data);
    }

    public function exportExcel()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $filter = $this->request->getGet('filter') ?? 'harian';
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $minggu = $this->request->getGet('minggu') ?? date('Y-m-d');

        $data = $this->getReportData($filter, $tanggal, $minggu, $bulan);

        $filename = "Laporan_Pendapatan_" . ucfirst($filter) . "_" . str_replace(' ', '_', $data['label']) . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('Modules\DashboardAdmin\Views\report_excel', $data);
    }

    private function getReportData($filter, $tanggal, $minggu, $bulan)
    {
        if ($filter === 'harian') {
            $start = $tanggal . ' 00:00:00';
            $end = $tanggal . ' 23:59:59';
            $label = date('d M Y', strtotime($tanggal));
        } elseif ($filter === 'mingguan') {
            $date = new \DateTime($minggu);
            $dayOfWeek = (int) $date->format('N'); // 1 (Monday) to 7 (Sunday)
            $date->modify('-' . ($dayOfWeek - 1) . ' days'); // set to Monday
            $start = $date->format('Y-m-d 00:00:00');
            $date->modify('+6 days'); // set to Sunday
            $end = $date->format('Y-m-d 23:59:59');
            $label = date('d M Y', strtotime($start)) . ' s/d ' . date('d M Y', strtotime($end));
        } else { // bulanan
            $start = $bulan . '-01 00:00:00';
            $end = date('Y-m-t 23:59:59', strtotime($start));
            $label = date('F Y', strtotime($start));
        }

        $db = \Config\Database::connect();
        $payments = $db->table('pembayaran')
            ->select('pembayaran.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit, reservasi.tipe as tipe_sewa, reservasi.total_jam')
            ->join('reservasi', 'pembayaran.reservasi_id = reservasi.id')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->where('pembayaran.dibayar_at >=', $start)
            ->where('pembayaran.dibayar_at <=', $end)
            ->orderBy('pembayaran.dibayar_at', 'ASC')
            ->get()->getResultArray();

        $total_pendapatan = 0;
        $total_transaksi = count($payments);
        $tunai_count = 0;
        $qris_count = 0;

        foreach ($payments as $pay) {
            $total_pendapatan += (int) $pay['jumlah'];
            if (strtolower($pay['metode']) === 'tunai') {
                $tunai_count++;
            } elseif (strtolower($pay['metode']) === 'qris') {
                $qris_count++;
            }
        }

        return [
            'payments' => $payments,
            'total_pendapatan' => $total_pendapatan,
            'total_transaksi' => $total_transaksi,
            'tunai_count' => $tunai_count,
            'qris_count' => $qris_count,
            'label' => $label,
            'filter' => $filter,
            'tanggal' => $tanggal,
            'minggu' => $minggu,
            'bulan' => $bulan,
        ];
    }
}
