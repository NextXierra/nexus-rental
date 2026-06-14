<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;

class CustomerController extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard/user');
        }

        return view('Modules\DashboardAdmin\Views\customer');
    }
}
