<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // 1. Menampilkan Halaman Login
    public function login()
    {
        return view('auth/login');
    }

    // 2. Menampilkan Halaman Register
    public function register()
    {
        return view('auth/register');
    }

    // 3. Proses Pendaftaran User Baru
    public function processRegister()
    {
        $userModel = new UserModel();

        // Ambil data dari form dan simpan ke database
        $userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            // Password di-hash (acak) agar aman, tidak teks biasa
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        // Setelah daftar, lempar ke halaman login
        return redirect()->to('/login')->with('msg', 'Registrasi Berhasil! Silakan Login.');
    }

    // 4. Proses Verifikasi Login
    public function processLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        // Jika user ada DAN password-nya cocok
        if ($user && password_verify($password, $user['password'])) {
            // Set data ke dalam Session (tanda sudah login)
            session()->set([
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'logged_in' => true
            ]);
            return redirect()->to('/dashboard');
        } else {
            // Jika salah, balikkan ke login dengan pesan error
            return redirect()->back()->with('error', 'Email atau Password salah!');
        }
    }

    // 5. Keluar dari Aplikasi
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}