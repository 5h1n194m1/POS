<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProductModel;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $productModel;

    public function __construct()
    {
        // Inisialisasi Model
        $this->penjualanModel = new PenjualanModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        // Menampilkan halaman kasir
        return view('penjualan/index');
    }

    /**
     * EKSEKUSI TUGAS: Fungsi Simpan Transaksi dengan Return ID JSON
     */
    public function save()
    {
        // 1. Validasi: pastikan user sudah login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg'    => 'Sesi berakhir, silakan login kembali.'
            ]);
        }

        // 2. Ambil data dari form POST
        $totalHarga = $this->request->getPost('total');
        $bayar      = $this->request->getPost('bayar');
        $kembalian  = $this->request->getPost('kembalian');

        // 3. Siapkan data untuk tabel 'penjualan'
        $data = [
            'user_id'     => session()->get('user_id'), // OTOMATIS AMBIL ID KASIR DARI SESSION
            'total_harga' => $totalHarga,
            'bayar'       => $bayar,
            'kembalian'   => $kembalian,
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        // 4. Simpan ke database
        $simpan = $this->penjualanModel->insert($data);
        
        // 5. Ambil ID terakhir yang baru saja masuk
        $insertID = $this->penjualanModel->getInsertID();

        // 6. Return Response dalam format JSON
        if ($simpan) {
            return $this->response->setJSON([
                'status' => 'success',
                'msg'    => 'Transaksi Berhasil Disimpan!',
                'id'     => $insertID // ID ini akan digunakan Frontend untuk cetak struk
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'msg'    => 'Gagal menyimpan transaksi ke database.'
            ]);
        }
    }
}