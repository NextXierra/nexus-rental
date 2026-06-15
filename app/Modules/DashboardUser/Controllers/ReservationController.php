<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\ReservationModel;
use Modules\DashboardAdmin\Models\UnitPsModel;
use Modules\DashboardAdmin\Models\PaymentModel;

class ReservationController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $reservationModel = new ReservationModel();

        $reservasiList = $reservationModel
            ->select('reservasi.*, unit_ps.nama_unit, pembayaran.metode as metode_bayar, pembayaran.status as status_bayar')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->join('pembayaran', 'reservasi.id = pembayaran.reservasi_id', 'left')
            ->where('pelanggan.user_id', $userId)
            ->orderBy('reservasi.created_at', 'DESC')
            ->paginate(10, 'reservations_user');

        $unitModel = new UnitPsModel();
        
        $unitList = $unitModel->where('status !=', 'maintenance')->orderBy('nama_unit', 'ASC')->findAll();
        $selectedUnitId = $this->request->getGet('unit_id');

        return view('Modules\DashboardUser\Views\reservation', [
            'reservations'   => $reservasiList,
            'unitList'       => $unitList,
            'selectedUnitId' => $selectedUnitId,
            'pager'          => $reservationModel->pager,
        ]);
    }

    public function store()
    {
        $rules = [
            'unit_id'          => 'required|integer',
            'tanggal_mulai'    => 'required|valid_date[Y-m-d]',
            'jam_mulai_hour'   => 'required',
            'jam_mulai_minute' => 'required',
            'durasi'           => 'required|integer|greater_than[0]',
            'metode'           => 'required|in_list[tunai,qris]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $unitId = $this->request->getPost('unit_id');
        $unitModel = new UnitPsModel();
        $unit = $unitModel->find($unitId);

        if (! $unit) {
            return redirect()->back()->withInput()->with('error', 'Unit PS tidak ditemukan.');
        }

        if ($unit['status'] === 'maintenance') {
            return redirect()->back()->withInput()->with('error', 'Unit PS sedang dalam perawatan (maintenance).');
        }

        $tanggal = $this->request->getPost('tanggal_mulai');
        $jamHour = $this->request->getPost('jam_mulai_hour');
        $jamMinute = $this->request->getPost('jam_mulai_minute');
        $waktuMulai = strtotime($tanggal . ' ' . $jamHour . ':' . $jamMinute);
        $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);

        if ($waktuMulai < time()) {
            return redirect()->back()->withInput()->with('error', 'Waktu mulai booking tidak boleh di masa lampau.');
        }

        $durasi = (int) $this->request->getPost('durasi');
        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        $db = \Config\Database::connect();

        $overlap = $db->table('reservasi')
            ->where('unit_id', $unitId)
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $waktuSelesaiFormatted)
            ->where('waktu_selesai >', $waktuMulaiFormatted)
            ->get()->getRowArray();

        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Unit PS tersebut sudah disewa pada jam tersebut.');
        }

        $userId = session()->get('user_id');
        $existingPelanggan = $db->table('pelanggan')->where('user_id', $userId)->get()->getRowArray();
        if ($existingPelanggan) {
            $pelangganId = $existingPelanggan['id'];
        } else {
            $userRow = $db->table('users')->where('id', $userId)->get()->getRowArray();
            $db->table('pelanggan')->insert([
                'user_id' => $userId,
                'nama'    => session()->get('nama'),
                'no_hp'   => $userRow['no_hp'] ?? null,
            ]);
            $pelangganId = $db->insertID();
        }

        $totalJam = $durasi;
        $hargaPerJam = (int) $unit['harga_per_jam'];
        $totalHarga = $totalJam * $hargaPerJam;

        $db->transStart();

        $reservasiModel = new ReservationModel();
        $reservasiId = $reservasiModel->insert([
            'pelanggan_id'  => $pelangganId,
            'unit_id'       => $unitId,
            'tipe'          => 'online',
            'waktu_mulai'   => $waktuMulaiFormatted,
            'waktu_selesai' => $waktuSelesaiFormatted,
            'total_jam'     => $totalJam,
            'harga_per_jam' => $hargaPerJam,
            'total_harga'   => $totalHarga,
            'status'        => 'pending', 
        ]);

        $metode = $this->request->getPost('metode');
        $statusPembayaran = ($metode === 'qris') ? 'sudah_bayar' : 'belum_bayar';

        $pembayaranModel = new PaymentModel();
        $pembayaranModel->insert([
            'reservasi_id' => $reservasiId,
            'jumlah'       => $totalHarga,
            'metode'       => $metode,
            'status'       => $statusPembayaran,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pengajuan booking.');
        }

        return redirect()->to('/dashboard/user/reservasi')->with('success', 'Pengajuan booking dikirim. Menunggu persetujuan admin.');
    }
}
