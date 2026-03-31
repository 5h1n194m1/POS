<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        // Memanggil Model melalui property agar efisien
        $this->productModel = new ProductModel();
    }

    /**
     * Tampilkan Daftar Barang
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title'    => 'Manajemen Stok Produk',
            'products' => $this->productModel->findAll()
        ];

        return view('product/index', $data);
    }

    /**
     * Proses Tambah Barang
     */
    public function save()
    {
        $rules = [
            'kode_produk' => [
                'rules'  => 'required|is_unique[produk.kode_produk]',
                'errors' => [
                    'required'  => 'Kode produk wajib diisi.',
                    'is_unique' => 'Kode produk {value} sudah ada dalam sistem!'
                ]
            ],
            'nama_produk' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'Nama produk wajib diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Gagal menyimpan! Periksa kembali inputan Anda.');
            return redirect()->back()->withInput();
        }

        $this->productModel->save([
            'kode_produk' => $this->request->getPost('kode_produk'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/product')->with('success', 'Data produk berhasil ditambahkan.');
    }

    /**
     * Proses Update Barang
     */
    public function update()
    {
        // Ambil ID dari input hidden di modal edit
        $id = $this->request->getPost('id');

        // Validasi input
        $rules = [
            'nama_produk' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal update: Data tidak valid.');
        }

        $this->productModel->update($id, [
            // Kode produk biasanya tidak diupdate karena jadi referensi nota/transaksi
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/product')->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Hapus Barang
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/product')->with('error', 'Data tidak ditemukan.');
        }

        $this->productModel->delete($id);
        return redirect()->to('/product')->with('success', 'Data produk berhasil dihapus.');
    }
}