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
            [
                'nama'     => 'Dewi Lestari',
                'email'    => 'dewi@gmail.com',
                'password' => 'user',
                'no_hp'    => '0812-9988-7766',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Eko Prasetyo',
                'email'    => 'eko@gmail.com',
                'password' => 'user',
                'no_hp'    => '0813-8877-6655',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Fajar Nugraha',
                'email'    => 'fajar@gmail.com',
                'password' => 'user',
                'no_hp'    => '0814-7766-5544',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Gita Permata',
                'email'    => 'gita@gmail.com',
                'password' => 'user',
                'no_hp'    => '0815-6655-4433',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Hendra Wijaya',
                'email'    => 'hendra@gmail.com',
                'password' => 'user',
                'no_hp'    => '0816-5544-3322',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Indah Cahyani',
                'email'    => 'indah@gmail.com',
                'password' => 'user',
                'no_hp'    => '0817-4433-2211',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Joko Susilo',
                'email'    => 'joko@gmail.com',
                'password' => 'user',
                'no_hp'    => '0818-3322-1100',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Kartika Sari',
                'email'    => 'kartika@gmail.com',
                'password' => 'user',
                'no_hp'    => '0819-2211-0099',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Lukman Hakim',
                'email'    => 'lukman@gmail.com',
                'password' => 'user',
                'no_hp'    => '0821-1100-9988',
                'role'     => 'pelanggan'
            ],
            [
                'nama'     => 'Mega Utami',
                'email'    => 'mega@gmail.com',
                'password' => 'user',
                'no_hp'    => '0822-0099-8877',
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
