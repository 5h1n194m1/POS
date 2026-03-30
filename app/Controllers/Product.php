<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    // Tampilkan Daftar Barang
    public function index()
    {
        // Proteksi Login
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $data = [
            'title'    => 'Data Produk',
            'products' => $this->productModel->findAll()
        ];
        return view('product/index', $data);
    }

    // Proses Tambah Barang
    public function save()
    {
        $this->productModel->save([
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kode_produk' => $this->request->getPost('kode_produk'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/product')->with('msg', 'Data berhasil ditambah!');
    }

    // Hapus Barang
    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/product')->with('msg', 'Data berhasil dihapus!');
    }
}