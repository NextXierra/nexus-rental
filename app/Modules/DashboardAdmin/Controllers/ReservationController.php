<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\ReservationModel;
use Modules\DashboardAdmin\Models\UnitModel;
use Modules\DashboardAdmin\Models\PaymentModel;

class ReservationController extends BaseController
{
    public function index()
    {
        $reservationModel = new ReservationModel();
        
        $pendingReservations = $reservationModel
            ->select('reservasi.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->where('reservasi.status', 'pending')
            ->orderBy('reservasi.created_at', 'DESC')
            ->findAll();
 
        $reservations = $reservationModel
            ->select('reservasi.*, pelanggan.nama as nama_pelanggan, unit_ps.nama_unit')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->where('reservasi.status !=', 'pending')
            ->orderBy('reservasi.created_at', 'DESC')
            ->paginate(10, 'reservations');
 
        $db = \Config\Database::connect();
        $pelangganList = $db->table('pelanggan')->orderBy('nama', 'ASC')->get()->getResultArray();
        $userList = $db->table('users')->where('role', 'pelanggan')->orderBy('nama', 'ASC')->get()->getResultArray();
        $unitModel = new UnitModel();
        
        $unitList = $unitModel->where('status !=', 'maintenance')->orderBy('nama_unit', 'ASC')->findAll();
        $allUnits = $unitModel->orderBy('nama_unit', 'ASC')->findAll();

        return view('Modules\DashboardAdmin\Views\reservation', [
            'pendingReservations' => $pendingReservations,
            'reservations' => $reservations,
            'pelangganList' => $pelangganList,
            'userList' => $userList,
            'unitList' => $unitList,
            'allUnits' => $allUnits,
            'pager' => $reservationModel->pager,
        ]);
    }

    public function store()
    {
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
            $rules['tanggal_mulai']   = 'required|valid_date[Y-m-d]';
            $rules['jam_mulai_hour']   = 'required';
            $rules['jam_mulai_minute'] = 'required';
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
            
            $existingPelanggan = $db->table('pelanggan')->where('user_id', $userId)->get()->getRowArray();
            if ($existingPelanggan) {
                $pelangganId = $existingPelanggan['id'];
            } else {
                
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
            
            $db->table('pelanggan')->insert([
                'user_id' => null,
                'nama'    => $this->request->getPost('nama'),
                'no_hp'   => $this->request->getPost('no_hp'),
            ]);
            $pelangganId = $db->insertID();
        }

        $unitId = $this->request->getPost('unit_id');
        $unitModel = new UnitModel();
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
            $tanggal = $this->request->getPost('tanggal_mulai');
            $jamHour = $this->request->getPost('jam_mulai_hour');
            $jamMinute = $this->request->getPost('jam_mulai_minute');
            $waktuMulai = strtotime($tanggal . ' ' . $jamHour . ':' . $jamMinute);
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        }

        $durasi = (int) $this->request->getPost('durasi');
        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        
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

        $reservasiModel = new ReservationModel();
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

        $pembayaranModel = new PaymentModel();
        $pembayaranModel->insert([
            'reservasi_id' => $reservasiId,
            'jumlah'       => $totalHarga,
            'metode'       => $this->request->getPost('metode'),
            'status'       => 'lunas',
        ]);

        
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
        $reservasiModel = new ReservationModel();
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

        $unitModel = new UnitModel();
        $unitModel->update($reservasi['unit_id'], ['status' => 'tersedia']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan reservasi.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi diselesaikan.');
    }

    public function cancel($id)
    {
        $reservasiModel = new ReservationModel();
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

        $unitModel = new UnitModel();
        $unitModel->update($reservasi['unit_id'], ['status' => 'tersedia']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal membatalkan reservasi.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi dibatalkan.');
    }

    public function checkAvailability()
    {
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

    public function checkUnits()
    {
        $tipe = $this->request->getGet('tipe');
        $durasi = (int) $this->request->getGet('durasi');

        if (! $tipe || ! $durasi) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
        }

        if ($tipe === 'offline') {
            $waktuMulai = time();
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        } else {
            $waktuMulaiStr = $this->request->getGet('waktu_mulai');
            if (! $waktuMulaiStr) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Waktu mulai harus diisi']);
            }
            $waktuMulai = strtotime($waktuMulaiStr);
            $waktuMulaiFormatted = date('Y-m-d H:i:s', $waktuMulai);
        }

        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        $db = \Config\Database::connect();
        
        $unitModel = new UnitModel();
        $units = $unitModel->where('status !=', 'maintenance')->orderBy('nama_unit', 'ASC')->findAll();

        $activeReservations = $db->table('reservasi')
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $waktuSelesaiFormatted)
            ->where('waktu_selesai >', $waktuMulaiFormatted)
            ->get()->getResultArray();

        $bookedUnitIds = array_column($activeReservations, 'unit_id');

        $resultUnits = [];
        foreach ($units as $unit) {
            $isBooked = in_array($unit['id'], $bookedUnitIds);
            $resultUnits[] = [
                'id'            => $unit['id'],
                'nama_unit'     => $unit['nama_unit'],
                'tipe'          => $unit['tipe'],
                'harga_per_jam' => $unit['harga_per_jam'],
                'is_booked'     => $isBooked,
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'units'  => $resultUnits
        ]);
    }

    public function approve($id)
    {
        $reservasiModel = new ReservationModel();
        $reservasi = $reservasiModel->find($id);

        if (! $reservasi || $reservasi['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan reservasi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        
        $overlap = $db->table('reservasi')
            ->where('unit_id', $reservasi['unit_id'])
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $reservasi['waktu_selesai'])
            ->where('waktu_selesai >', $reservasi['waktu_mulai'])
            ->get()->getRowArray();

        if ($overlap) {
            return redirect()->back()->with('error', 'Gagal menyetujui, unit PS bentrok dengan reservasi aktif lain.');
        }

        $db->transStart();

        $reservasiModel->update($id, ['status' => 'aktif']);

        $db->table('pembayaran')
            ->where('reservasi_id', $id)
            ->update(['status' => 'lunas']);

        $waktuMulai = strtotime($reservasi['waktu_mulai']);
        if ($waktuMulai <= time()) {
            $unitModel = new UnitModel();
            $unitModel->update($reservasi['unit_id'], ['status' => 'disewa']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses persetujuan.');
        }

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Reservasi berhasil disetujui.');
    }

    public function reject($id)
    {
        $reservasiModel = new ReservationModel();
        $reservasi = $reservasiModel->find($id);

        if (! $reservasi || $reservasi['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan reservasi tidak ditemukan.');
        }

        $reservasiModel->update($id, ['status' => 'dibatalkan']);

        return redirect()->to('/dashboard/admin/reservasi')->with('success', 'Permintaan reservasi ditolak.');
    }
}
