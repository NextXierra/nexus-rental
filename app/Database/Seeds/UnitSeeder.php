<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\DashboardAdmin\Models\UnitModel;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $unitModel = new UnitModel();

        $data = [
            ['nama_unit' => 'PS4-01', 'tipe' => 'PS4', 'harga_per_jam' => 10000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS4-02', 'tipe' => 'PS4', 'harga_per_jam' => 10000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS4-03', 'tipe' => 'PS4', 'harga_per_jam' => 10000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS4-04', 'tipe' => 'PS4', 'harga_per_jam' => 10000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS5-01', 'tipe' => 'PS5', 'harga_per_jam' => 15000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS5-02', 'tipe' => 'PS5', 'harga_per_jam' => 15000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS5-03', 'tipe' => 'PS5', 'harga_per_jam' => 15000, 'status' => 'tersedia'],
            ['nama_unit' => 'PS5-04', 'tipe' => 'PS5', 'harga_per_jam' => 15000, 'status' => 'tersedia'],
        ];

        foreach ($data as $unit) {
            if (! $unitModel->where('nama_unit', $unit['nama_unit'])->first()) {
                $unitModel->insert($unit);
            }
        }
    }
}
