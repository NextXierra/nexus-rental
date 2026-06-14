<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;

class DashboardUser extends BaseController
{
    public function index(): string
    {
        return view('Modules\DashboardUser\Views\dashboard');
    }
}
