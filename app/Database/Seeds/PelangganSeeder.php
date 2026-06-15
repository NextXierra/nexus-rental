<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pelanggan');

        // Offline customers (user_id = null)
        $offlineData = [
            ['nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'user_id' => null],
            ['nama' => 'Siti Aminah', 'no_hp' => '081298765432', 'user_id' => null],
            ['nama' => 'Andi Wijaya', 'no_hp' => '085711223344', 'user_id' => null],
            ['nama' => 'Rina Melati', 'no_hp' => '089988776655', 'user_id' => null],
            ['nama' => 'Aditya Pratama', 'no_hp' => '082122334455', 'user_id' => null],
            ['nama' => 'Bambang Pamungkas', 'no_hp' => '081344556677', 'user_id' => null],
            ['nama' => 'Citra Kirana', 'no_hp' => '087855667788', 'user_id' => null],
            ['nama' => 'Dian Sastrowardoyo', 'no_hp' => '081166778899', 'user_id' => null],
            ['nama' => 'Eka Saputra', 'no_hp' => '085277889900', 'user_id' => null],
            ['nama' => 'Farhan Ramadhan', 'no_hp' => '089688990011', 'user_id' => null],
        ];

        foreach ($offlineData as $row) {
            if (! $builder->where('nama', $row['nama'])->get()->getRow()) {
                $builder->insert($row);
            }
        }

        // Online customers (mapped from users table with role 'pelanggan')
        $userBuilder = $db->table('users');
        $onlineUsers = $userBuilder->where('role', 'pelanggan')->get()->getResultArray();

        foreach ($onlineUsers as $user) {
            // Check if already mapped
            $existing = $builder->where('user_id', $user['id'])->get()->getRow();
            if (! $existing) {
                $builder->insert([
                    'nama'    => $user['nama'],
                    'no_hp'   => $user['no_hp'],
                    'user_id' => $user['id']
                ]);
            }
        }
    }
}
