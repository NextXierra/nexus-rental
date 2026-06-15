<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        return view('Modules\DashboardAdmin\Views\profile');
    }
}
