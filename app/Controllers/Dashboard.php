<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Cek apakah user sudah punya tiket masuk (session logged_in)
        if (!session()->get('logged_in')) {
            // Jika tidak ada, usir ke halaman login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // 2. Jika ada, tampilkan halaman dashboard
        return view('dashboard/index', ['title' => 'Dashboard POS']);    }
}