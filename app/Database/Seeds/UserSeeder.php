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
                'username' => 'user',
                'email'    => 'user@nexusrental.com',
                'password' => 'user', // Password akan di-hash oleh model
                'role'     => 'customer'
            ],
            [
                'username' => 'vip',
                'email'    => 'vip@nexusrental.com',
                'password' => 'vip',
                'role'     => 'customer_vip'
            ],
            [
                'username' => 'admin',
                'email'    => 'admin@nexusrental.com',
                'password' => 'admin',
                'role'     => 'admin'
            ],
        ];

        foreach ($data as $user) {
            $userModel->save($user);
        }
    }
}
