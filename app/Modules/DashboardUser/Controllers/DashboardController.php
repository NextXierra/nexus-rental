<?php

namespace Modules\DashboardUser\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

        return view('Modules\DashboardUser\Views\dashboard');
    }
}
