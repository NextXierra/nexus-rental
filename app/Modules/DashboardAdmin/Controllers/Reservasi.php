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
        $unitList = $unitModel->where('status', 'tersedia')->orderBy('nama_unit', 'ASC')->findAll();
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

        $rules = [
            'status_pelanggan' => 'required|in_list[user,pelanggan,baru]',
            'unit_id'          => 'required|integer',
            'tipe'             => 'required|in_list[online,offline]',
            'waktu_mulai'      => 'required|valid_date[Y-m-d\TH:i]',
            'waktu_selesai'    => 'required|valid_date[Y-m-d\TH:i]',
            'metode'           => 'required|in_list[tunai,qris]',
        ];

        if ($statusPelanggan === 'user') {
            $rules['user_id'] = 'required|integer';
        } elseif ($statusPelanggan === 'pelanggan') {
            $rules['pelanggan_id'] = 'required|integer';
        } else {
            $rules['nama_baru'] = 'required|max_length[100]';
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
        } elseif ($statusPelanggan === 'pelanggan') {
            $pelangganId = $this->request->getPost('pelanggan_id');
        } else {
            // Pelanggan Baru
            $db->table('pelanggan')->insert([
                'user_id' => null,
                'nama'    => $this->request->getPost('nama_baru'),
                'no_hp'   => $this->request->getPost('no_hp_baru'),
            ]);
            $pelangganId = $db->insertID();
        }

        $unitId = $this->request->getPost('unit_id');
        $unitModel = new UnitPsModel();
        $unit = $unitModel->find($unitId);

        if (! $unit) {
            return redirect()->back()->withInput()->with('error', 'Unit PS tidak ditemukan.');
        }

        if ($unit['status'] !== 'tersedia') {
            return redirect()->back()->withInput()->with('error', 'Unit PS sedang tidak tersedia.');
        }

        $waktuMulai = strtotime($this->request->getPost('waktu_mulai'));
        $waktuSelesai = strtotime($this->request->getPost('waktu_selesai'));

        if ($waktuSelesai <= $waktuMulai) {
            return redirect()->back()->withInput()->with('error', 'Waktu selesai harus setelah waktu mulai.');
        }

        $totalJam = ceil(($waktuSelesai - $waktuMulai) / 3600);
        $hargaPerJam = (int) $unit['harga_per_jam'];
        $totalHarga = $totalJam * $hargaPerJam;

        $db->transStart();

        $reservasiModel = new ReservasiModel();
        $reservasiId = $reservasiModel->insert([
            'pelanggan_id'  => $pelangganId,
            'unit_id'       => $unitId,
            'tipe'          => $this->request->getPost('tipe'),
            'waktu_mulai'   => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
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

        $unitModel->update($unitId, ['status' => 'disewa']);

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
}
