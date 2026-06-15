<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\Login\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $data = [
            [
                'nama'     => 'Pelanggan',
                'email'    => 'pelanggan@nexusrental.com',
                'password' => 'pelanggan', // Password akan di-hash oleh model
                'no_hp'    => '08xx-xxxx-xxxx',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Admin',
                'email'    => 'admin@nexusrental.com',
                'password' => 'admin',
                'no_hp'    => '08xx-xxxx-xxxx',
                'role'     => 'admin'
            ],
            [
                'nama'     => 'admin',
                'email'    => 'admin',
                'password' => 'admin',
                'no_hp'    => '0812-3456-7890',
                'role'     => 'admin'
            ],
            [
                'nama'     => 'user',
                'email'    => 'user',
                'password' => 'user',
                'no_hp'    => '0812-3456-7891',
                'role'     => 'pelanggan'
            ],
        ];

        foreach ($data as $user) {
            if (! $userModel->where('email', $user['email'])->first()) {
                $userModel->save($user);
            }
        }
    }
}
