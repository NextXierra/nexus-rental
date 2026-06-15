<?php

namespace Modules\LandingPage\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\GameModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $playstationCards = [
            ['icon' => 'fa-television', 'title' => 'Layar 50 Inch'],
            ['icon' => 'fa-gamepad', 'title' => 'Playstation 4'],
            ['icon' => 'fa-gamepad', 'title' => 'Playstation 5', 'subtitle' => 'VIP'],
            ['image' => '/images/sofa.svg', 'title' => 'Sofa Premium'],
            ['icon' => 'fa-coffee', 'title' => 'Cafe'],
            ['icon' => 'fa-wifi', 'title' => 'Wifi'],
        ];

        $gameModel = new GameModel();
        $gamesFromDb = $gameModel->orderBy('nama_game', 'ASC')->findAll();

        $psGames = [];
        foreach ($gamesFromDb as $g) {
            $psGames[] = [
                'img'  => '/images/' . $g['gambar'],
                'name' => $g['nama_game']
            ];
        }

        if (empty($psGames)) {
            $psGames = [
                ['img' => '/images/pes2019.jpg', 'name' => 'Pro Evolution Soccer 2019'],
                ['img' => '/images/fifa2019.jpg', 'name' => 'FIFA 2019'],
                ['img' => '/images/spiderman.png', 'name' => 'Spider-man'],
            ];
        }

        $db = \Config\Database::connect();
        $units = $db->table('unit_ps')
            ->whereIn('status', ['tersedia', 'disewa'])
            ->orderBy('tipe', 'ASC')
            ->orderBy('nama_unit', 'ASC')
            ->get()->getResultArray();

        $availability = [];
        foreach ($units as $unit) {
            $status = ($unit['status'] === 'disewa') ? 'booked' : 'available';
            $time = '';

            if ($unit['status'] === 'disewa') {
                $now = date('Y-m-d H:i:s');
                $activeRes = $db->table('reservasi')
                    ->where('unit_id', $unit['id'])
                    ->where('status', 'aktif')
                    ->where('waktu_mulai <=', $now)
                    ->where('waktu_selesai >=', $now)
                    ->orderBy('waktu_selesai', 'DESC')
                    ->get()->getRowArray();

                if ($activeRes) {
                    $time = 'Selesai: ' . date('H:i', strtotime($activeRes['waktu_selesai']));
                } else {
                    $time = 'Sewa Aktif';
                }
            }

            $availability[] = [
                'name'   => $unit['nama_unit'] . ' (' . $unit['tipe'] . ')',
                'status' => $status,
                'time'   => $time
            ];
        }

        return view('Modules\LandingPage\Views\home', compact('playstationCards', 'psGames', 'availability'));
    }
}
