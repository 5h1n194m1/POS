<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Mengecek role user sebelum mengakses halaman tertentu
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // 2. Cek apakah role-nya 'admin'
        // Jika bukan admin (misal: kasir), lempar balik ke dashboard
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses Ditolak! Menu ini hanya untuk Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi untuk kasus ini
    }
}