<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        return view('Modules\DashboardUser\Views\profile', [
            'user' => $user,
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();
        
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'nama'              => 'required|min_length[3]|max_length[100]',
            'email'             => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'no_hp'             => 'permit_empty|min_length[8]|max_length[20]',
            'password_sekarang' => 'required',
        ];

        $newPassword = $this->request->getPost('password_baru');
        if (! empty($newPassword)) {
            $rules['password_baru'] = 'min_length[6]';
            $rules['konfirmasi_password_baru'] = 'required|matches[password_baru]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPasswordInput = $this->request->getPost('password_sekarang');
        if (! password_verify($currentPasswordInput, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Kata sandi saat ini salah.');
        }

        $updateData = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
        ];

        if (! empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $db->transStart();

        // Update Users
        $db->table('users')->where('id', $userId)->update($updateData);

        // Update Pelanggan
        $existingPelanggan = $db->table('pelanggan')->where('user_id', $userId)->get()->getRowArray();
        if ($existingPelanggan) {
            $db->table('pelanggan')->where('user_id', $userId)->update([
                'nama'  => $updateData['nama'],
                'no_hp' => $updateData['no_hp'],
            ]);
        } else {
            $db->table('pelanggan')->insert([
                'user_id' => $userId,
                'nama'    => $updateData['nama'],
                'no_hp'   => $updateData['no_hp'],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
        }

        // Update session
        session()->set('nama', $updateData['nama']);

        return redirect()->to('/dashboard/user/profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
