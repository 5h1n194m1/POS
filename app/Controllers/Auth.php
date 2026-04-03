<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function processRegister()
    {
        $rules = [
            'fullname'         => 'required|min_length[3]|max_length[255]',
            'username'         => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'  => trim($this->request->getPost('username')),
            'fullname'  => trim($this->request->getPost('fullname')),
            'email'     => trim($this->request->getPost('email')),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => 'kasir',
            'status'    => 'aktif',
            'is_active' => 1,
            'photo'     => null,
        ];

        if (! $this->userModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Registrasi gagal disimpan.');
        }

        return redirect()->to('/login')->with('msg', 'Registrasi berhasil! Silakan login.');
    }

    public function processLogin()
    {
        $login    = trim($this->request->getPost('login'));
        $password = $this->request->getPost('password');

        if ($login === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Username/email dan password wajib diisi.');
        }

        $user = $this->userModel
            ->groupStart()
                ->where('username', $login)
                ->orWhere('email', $login)
            ->groupEnd()
            ->first();

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Username atau email tidak ditemukan!');
        }

        if (! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah!');
        }

        if (($user['status'] ?? 'non-aktif') !== 'aktif' || (int) ($user['is_active'] ?? 0) !== 1) {
            return redirect()->back()->with('error', 'Akun Anda dinonaktifkan. Hubungi Admin.');
        }

        $this->userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s')
        ]);

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'fullname'  => $user['fullname'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'photo'     => $user['photo'] ?? null,
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}