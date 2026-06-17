<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\UnitModel;

class UnitController extends BaseController
{
    public function index()
    {
        $unitModel = new UnitModel();
        $units = $unitModel->orderBy('tipe', 'ASC')->orderBy('nama_unit', 'ASC')->paginate(10, 'units');

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        
        $activeBookedUnitIds = $db->table('reservasi')
            ->select('unit_id')
            ->where('status', 'aktif')
            ->where('waktu_mulai <=', $now)
            ->where('waktu_selesai >=', $now)
            ->get()->getResultArray();
        $bookedIds = array_column($activeBookedUnitIds, 'unit_id');

        foreach ($units as &$unit) {
            if (in_array($unit['id'], $bookedIds)) {
                $unit['status'] = 'disewa';
            }
        }

        return view('Modules\DashboardAdmin\Views\unit', [
            'units' => $units,
            'pager' => $unitModel->pager
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_unit'     => 'required|max_length[50]',
            'tipe'          => 'required|in_list[PS4,PS5]',
            'harga_per_jam' => 'required|integer|greater_than[0]',
            'status'        => 'required|in_list[tersedia,disewa,maintenance]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $unitModel = new UnitModel();
        $unitModel->insert([
            'nama_unit'     => $this->request->getPost('nama_unit'),
            'tipe'          => $this->request->getPost('tipe'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'status'        => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/admin/unit')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama_unit'     => 'required|max_length[50]',
            'tipe'          => 'required|in_list[PS4,PS5]',
            'harga_per_jam' => 'required|integer|greater_than[0]',
            'status'        => 'required|in_list[tersedia,disewa,maintenance]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $unitModel = new UnitModel();
        $unitModel->update($id, [
            'nama_unit'     => $this->request->getPost('nama_unit'),
            'tipe'          => $this->request->getPost('tipe'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'status'        => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/admin/unit')->with('success', 'Unit berhasil diperbarui.');
    }

    public function delete($id)
    {
        $unitModel = new UnitModel();
        $unitModel->delete($id);

        return redirect()->to('/dashboard/admin/unit')->with('success', 'Unit berhasil dihapus.');
    }
}
