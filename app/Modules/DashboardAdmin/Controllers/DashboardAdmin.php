<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;

class DashboardAdmin extends BaseController
{
    public function index(): string
    {
        return view('Modules\DashboardAdmin\Views\dashboard');
    }
}
