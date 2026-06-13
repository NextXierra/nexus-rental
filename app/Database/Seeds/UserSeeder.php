<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\Login\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new User();

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
        ];

        foreach ($data as $user) {
            $userModel->save($user);
        }
    }
}
