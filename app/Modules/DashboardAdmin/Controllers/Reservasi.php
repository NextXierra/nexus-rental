<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\Reservasi as ReservasiModel;
use Modules\DashboardAdmin\Models\UnitPs as UnitPsModel;
use Modules\DashboardAdmin\Models\Pembayaran as PembayaranModel;

class Reservasi extends BaseController
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
        $reservasiList = $db->table('reservasi')
            ->select('reservasi.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->orderBy('reservasi.created_at', 'DESC')
            ->get()->getResultArray();

        $pelangganList = $db->table('pelanggan')->orderBy('nama', 'ASC')->get()->getResultArray();
        $userList = $db->table('users')->where('role', 'pelanggan')->orderBy('nama', 'ASC')->get()->getResultArray();
        $unitModel = new UnitPsModel();
        // Tampilkan semua unit kecuali yang sedang maintenance
        $unitList = $unitModel->where('status !=', 'maintenance')->orderBy('nama_unit', 'ASC')->findAll();
        $allUnits = $unitModel->orderBy('nama_unit', 'ASC')->findAll();

        return view('Modules\DashboardAdmin\Views\reservasi', [
            'reservations' => $reservasiList,
            'pelangganList' => $pelangganList,
            'userList' => $userList,
            'unitList' => $unitList,
            'allUnits' => $allUnits,
        ]);
    }

    public function store()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $statusPelanggan = $this->request->getPost('status_pelanggan');
        $tipeLayanan = $this->request->getPost('tipe');

        $rules = [
            'status_pelanggan' => 'required|in_list[user,pelanggan]',
            'unit_id'          => 'required|integer',
            'tipe'             => 'required|in_list[online,offline]',
            'durasi'           => 'required|integer|greater_than[0]',
            'metode'           => 'required|in_list[tunai,qris]',
        ];

        if ($tipeLayanan === 'online') {
            $rules['waktu_mulai'] = 'required|valid_date[Y-m-d\TH:i]';
        }

        if ($statusPelanggan === 'user') {
            $rules['user_id'] = 'required|integer';
        } else {
            $rules['nama'] = 'required|max_length[100]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $pelangganId = null;

        if ($statusPelanggan === 'user') {
            $userId = $this->request->getPost('user_id');
            // Cek apakah user sudah punya data pelanggan
            $existingPelanggan = $db->table('pelanggan')->where('user_id', $userId)->get()->getRowArray();
            if ($existingPelanggan) {
                $pelangganId = $existingPelanggan['id'];
            } else {
                // Ambil data user untuk disalin ke pelanggan
                $userData = $db->table('users')->where('id', $userId)->get()->getRowArray();
                if (! $userData) {
                    return redirect()->back()->withInput()->with('error', 'User tidak ditemukan.');
                }
                $db->table('pelanggan')->insert([
                    'user_id' => $userId,
                    'nama'    => $userData['nama'],
                    'no_hp'   => $userData['no_hp'],
                ]);
                $pelangganId = $db->insertID();
            }
        } else {
            // Pelanggan Biasa (Non-Member) -> Buat baru tiap transaksi
            $db->table('pelanggan')->insert([
                'user_id' => null,
                'nama'    => $this->request->getPost('nama'),
                'no_hp'   => $this->request->getPost('no_hp'),
            ]);
            $pelangganId = $db->insertID();
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

        if ($tipeLayanan === 'offline') {
            $waktuMulai = time();
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        } else {
            $waktuMulai = strtotime($this->request->getPost('waktu_mulai'));
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        }

        $durasi = (int) $this->request->getPost('durasi');
        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        // Cek overlap reservasi aktif
        $overlap = $db->table('reservasi')
            ->where('unit_id', $unitId)
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $waktuSelesaiFormatted)
            ->where('waktu_selesai >', $waktuMulaiFormatted)
            ->get()->getRowArray();

        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Unit PS tersebut sudah disewa pada jam tersebut.');
        }

        $totalJam = $durasi;
        $hargaPerJam = (int) $unit['harga_per_jam'];
        $totalHarga = $totalJam * $hargaPerJam;

        $db->transStart();

        $reservasiModel = new ReservasiModel();
        $reservasiId = $reservasiModel->insert([
            'pelanggan_id'  => $pelangganId,
            'unit_id'       => $unitId,
            'tipe'          => $tipeLayanan,
            'waktu_mulai'   => $waktuMulaiFormatted,
            'waktu_selesai' => $waktuSelesaiFormatted,
            'total_jam'     => $totalJam,
            'harga_per_jam' => $hargaPerJam,
            'total_harga'   => $totalHarga,
            'status'        => 'aktif',
        ]);

        $pembayaranModel = new PembayaranModel();
        $pembayaranModel->insert([
            'reservasi_id' => $reservasiId,
            'jumlah'       => $totalHarga,
            'metode'       => $this->request->getPost('metode'),
            'status'       => 'lunas',
        ]);

        // Update status unit jika waktu mulai adalah saat ini atau lampau
        if ($waktuMulai <= time()) {
            $unitModel->update($unitId, ['status' => 'disewa']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat reservasi.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi berhasil dibuat.');
    }

    public function complete($id)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $reservasiModel = new ReservasiModel();
        $reservasi = $reservasiModel->find($id);

        if (! $reservasi) {
            return redirect()->back()->with('error', 'Reservasi tidak ditemukan.');
        }

        if ($reservasi['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Status reservasi sudah tidak aktif.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $reservasiModel->update($id, ['status' => 'selesai']);

        $unitModel = new UnitPsModel();
        $unitModel->update($reservasi['unit_id'], ['status' => 'tersedia']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan reservasi.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi diselesaikan.');
    }

    public function cancel($id)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        $reservasiModel = new ReservasiModel();
        $reservasi = $reservasiModel->find($id);

        if (! $reservasi) {
            return redirect()->back()->with('error', 'Reservasi tidak ditemukan.');
        }

        if ($reservasi['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Status reservasi sudah tidak aktif.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $reservasiModel->update($id, ['status' => 'dibatalkan']);

        $unitModel = new UnitPsModel();
        $unitModel->update($reservasi['unit_id'], ['status' => 'tersedia']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal membatalkan reservasi.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi dibatalkan.');
    }

    public function checkAvailability()
    {
        if (! session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['available' => false, 'message' => 'Unauthorized']);
        }

        $unitId = $this->request->getGet('unit_id');
        $tipe = $this->request->getGet('tipe');
        $durasi = (int) $this->request->getGet('durasi');

        if (! $unitId || ! $tipe || ! $durasi) {
            return $this->response->setJSON(['available' => false, 'message' => 'Parameter tidak lengkap']);
        }

        if ($tipe === 'offline') {
            $waktuMulai = time();
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        } else {
            $waktuMulaiStr = $this->request->getGet('waktu_mulai');
            if (! $waktuMulaiStr) {
                return $this->response->setJSON(['available' => false, 'message' => 'Waktu mulai harus diisi']);
            }
            $waktuMulai = strtotime($waktuMulaiStr);
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        }

        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        $db = \Config\Database::connect();
        $overlap = $db->table('reservasi')
            ->where('unit_id', $unitId)
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $waktuSelesaiFormatted)
            ->where('waktu_selesai >', $waktuMulaiFormatted)
            ->get()->getRowArray();

        if ($overlap) {
            return $this->response->setJSON([
                'available' => false, 
                'message' => 'Unit PS sudah disewa pada jam tersebut (' . date('H:i', strtotime($overlap['waktu_mulai'])) . ' - ' . date('H:i', strtotime($overlap['waktu_selesai'])) . ')'
            ]);
        }

        return $this->response->setJSON(['available' => true, 'message' => 'Unit PS tersedia']);
    }
}
