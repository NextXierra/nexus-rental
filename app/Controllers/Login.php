<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new User();
        $userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }

    public function processLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new User();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'logged_in' => true,
            ]);
            return redirect()->to('/'); // Redirect to dashboard or home
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
