<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Tampilkan Halaman Utama (Hanya Load View)
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Manajemen Stok Produk',
            // Kita tidak mengirim data 'products' di sini lagi, 
            // karena data akan ditarik via AJAX nanti.
        ];

        return view('product/index', $data);
    }

    /**
     * Ambil Data JSON untuk Tabel (Dijalankan via AJAX)
     */
    public function listData()
    {
        try {
            $products = $this->productModel->orderBy('id', 'DESC')->findAll();
            
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $products
            ]);
        } catch (\Exception $e) {
            // Jika ada error, kirimkan pesan error dalam format JSON agar AJAX tidak 'hang'
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Proses Tambah Barang (AJAX)
     */
    public function save()
    {
        $rules = [
            'kode_produk' => 'required|is_unique[produk.kode_produk]',
            'nama_produk' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
                'token'  => csrf_hash() // Kirim token baru jika gagal
            ]);
        }

        $this->productModel->save([
            'kode_produk' => $this->request->getPost('kode_produk'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Produk berhasil ditambahkan!',
            'token'   => csrf_hash() // Kirim token baru untuk request selanjutnya
        ]);
    }

    /**
     * Proses Update Barang (AJAX)
     */
    public function update()
    {
        $id = $this->request->getPost('id');
        
        $rules = [
            'nama_produk' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
                'token'  => csrf_hash()
            ]);
        }

        $this->productModel->update($id, [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Produk berhasil diperbarui!',
            'token'   => csrf_hash()
        ]);
    }

    /**
     * Hapus Barang (AJAX)
     */
    public function delete($id)
    {
        if ($this->productModel->delete($id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Produk berhasil dihapus.',
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menghapus data.',
            'token'   => csrf_hash()
        ], 400);
    }
}