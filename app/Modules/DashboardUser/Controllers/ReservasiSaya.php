<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\Reservasi as ReservasiModel;
use Modules\DashboardAdmin\Models\UnitPs as UnitPsModel;
use Modules\DashboardAdmin\Models\Pembayaran as PembayaranModel;

class ReservasiSaya extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Ambil data reservasi milik user ini
        $reservasiList = $db->table('reservasi')
            ->select('reservasi.*, unit_ps.nama_unit, pembayaran.metode as metode_bayar, pembayaran.status as status_bayar')
            ->join('pelanggan', 'reservasi.pelanggan_id = pelanggan.id')
            ->join('unit_ps', 'reservasi.unit_id = unit_ps.id')
            ->join('pembayaran', 'reservasi.id = pembayaran.reservasi_id', 'left')
            ->where('pelanggan.user_id', $userId)
            ->orderBy('reservasi.created_at', 'DESC')
            ->get()->getResultArray();

        $unitModel = new UnitPsModel();
        // Tampilkan semua unit kecuali yang sedang maintenance
        $unitList = $unitModel->where('status !=', 'maintenance')->orderBy('nama_unit', 'ASC')->findAll();

        return view('Modules\DashboardUser\Views\reservasi_saya', [
            'reservations' => $reservasiList,
            'unitList'     => $unitList,
        ]);
    }

    public function store()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

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

        // Validasi agar tidak booking di waktu lampau
        if ($waktuMulai < time()) {
            return redirect()->back()->withInput()->with('error', 'Waktu mulai booking tidak boleh di masa lampau.');
        }

        $durasi = (int) $this->request->getPost('durasi');
        $waktuSelesaiFormatted = date('Y-m-d H:i:s', $waktuMulai + ($durasi * 3600));

        $db = \Config\Database::connect();

        // Cek overlap dengan reservasi yang AKTIF (approved)
        $overlap = $db->table('reservasi')
            ->where('unit_id', $unitId)
            ->where('status', 'aktif')
            ->where('waktu_mulai <', $waktuSelesaiFormatted)
            ->where('waktu_selesai >', $waktuMulaiFormatted)
            ->get()->getRowArray();

        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Unit PS tersebut sudah disewa pada jam tersebut.');
        }

        // Cari atau buat record pelanggan untuk user_id ini
        $userId = session()->get('user_id');
        $existingPelanggan = $db->table('pelanggan')->where('user_id', $userId)->get()->getRowArray();
        if ($existingPelanggan) {
            $pelangganId = $existingPelanggan['id'];
        } else {
            // Dapatkan no hp user dari database jika tidak ada di session
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

        $reservasiModel = new ReservasiModel();
        $reservasiId = $reservasiModel->insert([
            'pelanggan_id'  => $pelangganId,
            'unit_id'       => $unitId,
            'tipe'          => 'online',
            'waktu_mulai'   => $waktuMulaiFormatted,
            'waktu_selesai' => $waktuSelesaiFormatted,
            'total_jam'     => $totalJam,
            'harga_per_jam' => $hargaPerJam,
            'total_harga'   => $totalHarga,
            'status'        => 'pending', // Menunggu approval admin
        ]);

        $metode = $this->request->getPost('metode');
        $statusPembayaran = ($metode === 'qris') ? 'sudah_bayar' : 'belum_bayar';

        $pembayaranModel = new PembayaranModel();
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
