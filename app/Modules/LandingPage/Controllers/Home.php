<?php

namespace Modules\LandingPage\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index(): string
    {
        $playstationCards = [
            ['icon' => 'fa-money', 'title' => 'Harga mulai dari IDR 8.000'],
            ['icon' => 'fa-gamepad', 'title' => 'Game PS 4 Terbaru'],
            ['image' => '/images/Martz90-Circle-Addon2-Playstation.ico', 'title' => 'Terdapat 9 console PS 4'],
            ['icon' => 'fa-television', 'title' => 'TV 32 ~ 50 inch'],
            ['icon' => 'fa-wifi', 'title' => 'free wifi', 'subtitle' => 'Pass : Nexusrentalps'],
            ['image' => '/images/Refrigeration-Icon.png', 'title' => 'FULL AC'],
        ];

        $psGames = [
            ['img' => '/images/pes2019.jpg', 'name' => 'Pro Evolution Soccer 2019'],
            ['img' => '/images/fifa2019.jpg', 'name' => 'FIFA 2019'],
            ['img' => '/images/spiderman.png', 'name' => 'Spider-man'],
            ['img' => '/images/pes2018.jpg', 'name' => 'Pro Evolution Soccer 2018'],
            ['img' => '/images/ps4fifa18.jpg', 'name' => 'FIFA 2018'],
            ['img' => '/images/god-of-war-4-day-one-edition.jpg', 'name' => 'God Of War 4'],
            ['img' => '/images/Grand-Theft-Auto-V-PS4-Box-Art.jpg', 'name' => 'Grand Theft Auto V'],
            ['img' => '/images/capcom-2-1003801.jpg', 'name' => 'Marvel vs. Capcom'],
            ['img' => '/images/nsuns_legacy_ps4_3d_pegi_usk_1499157554.jpg', 'name' => 'Naruto Ultimate Ninja Storm Legacy'],
            ['img' => '/images/81HNE-%2BY6WL._SL1500_.jpg', 'name' => 'Tekken 7'],
            ['img' => '/images/injustice.jpg', 'name' => 'Injustice 2'],
            ['img' => '/images/ps4-the-witcher-3-wild-hunt_1.jpg', 'name' => 'The Witcher 3'],
            ['img' => '/images/81Oqvv6OxHL._SL1500_.jpg', 'name' => 'Need For Speed Rivals'],
            ['img' => '/images/91iH-qAxe7L._SL1500_.jpg', 'name' => 'WWE 2017'],
            ['img' => '/images/Horizon-zero-dawn-box-art.jpg', 'name' => 'Horizon Zero Dawn'],
            ['img' => '/images/ufc_2_PS4_1_front_fvlb_3602794547772389271.jpg', 'name' => 'UFC 2'],
            ['img' => '/images/C0R4myqWgAERMF2.jpg', 'name' => 'Crash Bandicot N Sane Trilogy'],
            ['img' => '/images/1390942.jpg', 'name' => 'Battlefield 4'],
        ];

        $availability = [
            ['name' => 'Playstation 1', 'status' => 'available'],
            ['name' => 'Playstation 2', 'status' => 'available'],
            ['name' => 'Playstation 3', 'status' => 'available'],
            ['name' => 'Playstation 4', 'status' => 'available'],
            ['name' => 'Playstation 5', 'status' => 'available'],
            ['name' => 'Playstation 6', 'status' => 'available'],
            ['name' => 'Playstation 7', 'status' => 'available'],
            ['name' => 'Playstation 8', 'status' => 'booked', 'time' => 'Selesai pukul 21 : 21'],
            ['name' => 'Playstation VIP', 'status' => 'available'],
        ];

        return view('Modules\LandingPage\Views\home', compact('playstationCards', 'psGames', 'availability'));
    }
}
