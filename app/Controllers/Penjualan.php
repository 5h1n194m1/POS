<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\ProductModel;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $detailModel;
    protected $productModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detailModel    = new PenjualanDetailModel();
        $this->productModel   = new ProductModel();
    }

    /**
     * Proses Simpan Transaksi
     */
    public function save()
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Keranjang kosong!']);
        }

        try {
            // 1. Simpan Header Penjualan
            $dataPenjualan = [
                'user_id'     => session()->get('user_id'),
                'total_harga' => $this->request->getPost('total'),
                'bayar'       => $this->request->getPost('bayar'),
                'kembalian'   => $this->request->getPost('kembalian'),
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            if (!$this->penjualanModel->insert($dataPenjualan)) {
                throw new \Exception("Gagal simpan data penjualan utama.");
            }

            $penjualan_id = $this->penjualanModel->getInsertID();

            // 2. Simpan Detail & Kurangi Stok
            foreach ($cart as $id => $item) {
                // Simpan ke tabel penjualan_detail
                $this->detailModel->insert([
                    'penjualan_id' => $penjualan_id,
                    'product_id'   => $id,
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['subtotal']
                ]);

                // Update stok di tabel 'produk'
                // Menggunakan false pada parameter ketiga set() agar query 'stok - x' tidak di-escape sebagai string
                $this->productModel->where('id', $id)
                                   ->set('stok', "stok - {$item['qty']}", false)
                                   ->update();
            }

            // 3. Hapus Keranjang setelah sukses
            session()->remove('cart');

            return $this->response->setJSON([
                'status' => 'success', 
                'id'     => $penjualan_id
            ]);

        } catch (\Exception $e) {
            // Jika error, kirim pesan error asli ke browser
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'msg'    => $e->getMessage()
            ]);
        }
    }

    /**
     * Fungsi Print Nota
     */
    public function print_nota($id)
    {
        $penjualan = $this->penjualanModel->find($id);

        if (!$penjualan) {
            return "Data transaksi tidak ditemukan.";
        }

        $db = \Config\Database::connect();
        
        // Join ke tabel 'produk' sesuai dengan ProductModel Anda
        $details = $db->table('penjualan_detail')
                      ->join('produk', 'produk.id = penjualan_detail.product_id')
                      ->where('penjualan_id', $id)
                      ->get()
                      ->getResultArray();

        $data = [
            'penjualan' => $penjualan,
            'details'   => $details
        ];

        return view('kasir/nota', $data);
    }
}