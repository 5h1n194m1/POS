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

    // 4. Proses Verifikasi Login (DIREVISI)
    public function processLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        // --- KODE REVISI DIMULAI DI SINI ---
        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'fullname'  => $user['fullname'], // Mengambil nama asli dari database
                'role'      => $user['role'],     // Mengambil jabatan (admin/kasir)
                'logged_in' => true,
            ];
            
            session()->set($sessionData);
            return redirect()->to('/dashboard');
        } else {
            // Jika salah, balikkan ke login dengan pesan error
            return redirect()->back()->with('error', 'Email atau Password salah!');
        }
        // --- KODE REVISI SELESAI ---
    }

    // 5. Keluar dari Aplikasi
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}