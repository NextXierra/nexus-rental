<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\PelangganModel;

class CustomerController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fetch pelanggan with left join to users to get their linked account email
        $customers = $db->table('pelanggan')
            ->select('pelanggan.*, users.email')
            ->join('users', 'pelanggan.user_id = users.id', 'left')
            ->orderBy('pelanggan.nama', 'ASC')
            ->get()->getResultArray();

        // Fetch user accounts with role 'pelanggan' for dropdown selection
        $users = $db->table('users')
            ->where('role', 'pelanggan')
            ->orderBy('nama', 'ASC')
            ->get()->getResultArray();

        return view('Modules\DashboardAdmin\Views\customer', [
            'customers' => $customers,
            'users'     => $users,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'    => 'required|max_length[100]',
            'no_hp'   => 'permit_empty|max_length[20]',
            'user_id' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->request->getPost('user_id');
        $userId = $userId === '' ? null : (int)$userId;

        $pelangganModel = new PelangganModel();
        $pelangganModel->insert([
            'nama'    => $this->request->getPost('nama'),
            'no_hp'   => $this->request->getPost('no_hp'),
            'user_id' => $userId,
        ]);

        return redirect()->to('/dashboard/admin/pelanggan')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama'    => 'required|max_length[100]',
            'no_hp'   => 'permit_empty|max_length[20]',
            'user_id' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->request->getPost('user_id');
        $userId = $userId === '' ? null : (int)$userId;

        $pelangganModel = new PelangganModel();
        $pelangganModel->update($id, [
            'nama'    => $this->request->getPost('nama'),
            'no_hp'   => $this->request->getPost('no_hp'),
            'user_id' => $userId,
        ]);

        return redirect()->to('/dashboard/admin/pelanggan')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $pelangganModel = new PelangganModel();
        $pelangganModel->delete($id);

        return redirect()->to('/dashboard/admin/pelanggan')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
