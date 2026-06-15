<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\PaymentModel;

class PaymentController extends BaseController
{
    public function index()
    {
        $paymentModel = new PaymentModel();
        
        $pembayaranList = $paymentModel
            ->select('pembayaran.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit')
            ->join('reservasi', 'pembayaran.reservasi_id = reservasi.id')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->orderBy('pembayaran.dibayar_at', 'DESC')
            ->paginate(10, 'payments');

        return view('Modules\DashboardAdmin\Views\payment', [
            'payments' => $pembayaranList,
            'pager' => $paymentModel->pager
        ]);
    }
}
