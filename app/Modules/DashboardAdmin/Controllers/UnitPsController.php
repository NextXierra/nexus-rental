<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\UnitPsModel;

class UnitPsController extends BaseController
{
    public function index()
    {
        $unitModel = new UnitPsModel();

        return view('Modules\DashboardAdmin\Views\unit_ps', [
            'units' => $unitModel->orderBy('tipe', 'ASC')->orderBy('nama_unit', 'ASC')->paginate(10, 'units'),
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

        $unitModel = new UnitPsModel();
        $unitModel->insert([
            'nama_unit'     => $this->request->getPost('nama_unit'),
            'tipe'          => $this->request->getPost('tipe'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'status'        => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/admin/unit-ps')->with('success', 'Unit PS berhasil ditambahkan.');
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

        $unitModel = new UnitPsModel();
        $unitModel->update($id, [
            'nama_unit'     => $this->request->getPost('nama_unit'),
            'tipe'          => $this->request->getPost('tipe'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'status'        => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/admin/unit-ps')->with('success', 'Unit PS berhasil diperbarui.');
    }

    public function delete($id)
    {
        $unitModel = new UnitPsModel();
        $unitModel->delete($id);

        return redirect()->to('/dashboard/admin/unit-ps')->with('success', 'Unit PS berhasil dihapus.');
    }
}
