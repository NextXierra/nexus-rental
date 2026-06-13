<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pelanggan');

        $data = [
            ['nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'user_id' => null],
            ['nama' => 'Siti Aminah', 'no_hp' => '081298765432', 'user_id' => null],
            ['nama' => 'Andi Wijaya', 'no_hp' => '085711223344', 'user_id' => null],
            ['nama' => 'Rina Melati', 'no_hp' => '089988776655', 'user_id' => null],
        ];

        foreach ($data as $row) {
            if (! $builder->where('nama', $row['nama'])->get()->getRow()) {
                $builder->insert($row);
            }
        }
    }
}
