<?php

namespace Modules\Login\Controllers;

use App\Controllers\BaseController;
use Modules\Login\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        return view('Modules\Login\Views\login');
    }

    public function register()
    {
        return view('Modules\Login\Views\register');
    }

    public function processRegister()
    {
        $rules = [
            'nama'         => 'required|min_length[3]|max_length[100]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'no_hp'    => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }

    public function processLogin()
    {
        $rules = [
            'email'    => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'user_id'   => $user['id'],
                'nama'      => $user['nama'],
                'role'      => $user['role'],
                'logged_in' => true,
            ]);
            if ($user['role'] === 'admin') {
                return redirect()->to('/dashboard/admin');
            }

            return redirect()->to('/dashboard/user');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
