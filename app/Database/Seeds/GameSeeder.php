<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_game' => 'Pro Evolution Soccer 2019', 'gambar' => 'pes2019.jpg'],
            ['nama_game' => 'FIFA 2019', 'gambar' => 'fifa2019.jpg'],
            ['nama_game' => 'Spider-man', 'gambar' => 'spiderman.png'],
            ['nama_game' => 'Pro Evolution Soccer 2018', 'gambar' => 'pes2018.jpg'],
            ['nama_game' => 'FIFA 2018', 'gambar' => 'ps4fifa18.jpg'],
            ['nama_game' => 'God Of War 4', 'gambar' => 'god-of-war-4-day-one-edition.jpg'],
            ['nama_game' => 'Grand Theft Auto V', 'gambar' => 'Grand-Theft-Auto-V-PS4-Box-Art.jpg'],
            ['nama_game' => 'Marvel vs. Capcom', 'gambar' => 'capcom-2-1003801.jpg'],
            ['nama_game' => 'Naruto Ultimate Ninja Storm Legacy', 'gambar' => 'nsuns_legacy_ps4_3d_pegi_usk_1499157554.jpg'],
            ['nama_game' => 'Tekken 7', 'gambar' => '81HNE-+Y6WL._SL1500_.jpg'],
            ['nama_game' => 'Injustice 2', 'gambar' => 'injustice.jpg'],
            ['nama_game' => 'The Witcher 3', 'gambar' => 'ps4-the-witcher-3-wild-hunt_1.jpg'],
            ['nama_game' => 'Need For Speed Rivals', 'gambar' => '81Oqvv6OxHL._SL1500_.jpg'],
            ['nama_game' => 'WWE 2017', 'gambar' => '91iH-qAxe7L._SL1500_.jpg'],
            ['nama_game' => 'Horizon Zero Dawn', 'gambar' => 'Horizon-zero-dawn-box-art.jpg'],
            ['nama_game' => 'UFC 2', 'gambar' => 'ufc_2_PS4_1_front_fvlb_3602794547772389271.jpg'],
            ['nama_game' => 'Crash Bandicot N Sane Trilogy', 'gambar' => 'C0R4myqWgAERMF2.jpg'],
            ['nama_game' => 'Battlefield 4', 'gambar' => '1390942.jpg'],
        ];

        $db = \Config\Database::connect();
        $db->table('games')->insertBatch($data);
    }
}
