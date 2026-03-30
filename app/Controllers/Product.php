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
        // Proteksi Login - Sebaiknya gunakan Filter CI4 untuk skala profesional, 
        // namun saya pertahankan di sini sesuai kode asli Anda.
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
     * Proses Tambah Barang dengan Validasi Duplikasi
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
            'rules'  => 'required|is_unique[produk.nama_produk]',
            'errors' => [
                'required'  => 'Nama produk wajib diisi.',
                'is_unique' => 'Nama produk ini sudah terdaftar.'
            ]
        ]
    ];

    if (!$this->validate($rules)) {
        // PENTING: Set 'error' untuk memicu Pop-up SweetAlert
        session()->setFlashdata('error', 'Gagal menyimpan! Terdeteksi duplikasi data atau input tidak valid.');
        
        // redirect back dengan input lama dan pesan error spesifik kolom
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // ... sisa kode simpan ...
    $this->productModel->save([
        'nama_produk' => $this->request->getVar('nama_produk'),
        'kode_produk' => $this->request->getVar('kode_produk'),
        'kategori'    => $this->request->getVar('kategori'),
        'harga_beli'  => $this->request->getVar('harga_beli'),
        'harga_jual'  => $this->request->getVar('harga_jual'),
        'stok'        => $this->request->getVar('stok'),
    ]);

    return redirect()->to('/product')->with('success', 'Data produk berhasil ditambahkan.');
}
   

    /**
     * Hapus Barang
     */
    public function delete($id)
    {
        // Pastikan data ada sebelum dihapus (Best Practice)
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/product')->with('error', 'Data tidak ditemukan atau sudah dihapus.');
        }

        $this->productModel->delete($id);
        
        // Konsisten menggunakan 'success' agar ditangkap SweetAlert di View
        return redirect()->to('/product')->with('success', 'Data produk berhasil dihapus dari database.');
    }

    public function update($id)
{
    $model = new \App\Models\ProductModel();

    // Validasi input
    if (!$this->validate([
        'nama_produk' => 'required',
        'kode_produk' => "required|is_unique[products.kode_produk,id,{$id}]", // Unik kecuali untuk ID ini sendiri
        'harga_beli'  => 'required|numeric',
        'harga_jual'  => 'required|numeric',
        'stok'        => 'required|numeric',
    ])) {
        return redirect()->back()->withInput()->with('error', 'Gagal update: Data tidak valid atau Kode Produk sudah ada.');
    }

    $model->update($id, [
        'kode_produk' => $this->request->getPost('kode_produk'),
        'nama_produk' => $this->request->getPost('nama_produk'),
        'kategori'    => $this->request->getPost('kategori'),
        'harga_beli'  => $this->request->getPost('harga_beli'),
        'harga_jual'  => $this->request->getPost('harga_jual'),
        'stok'        => $this->request->getPost('stok'),
    ]);

    return redirect()->to('/product')->with('success', 'Data produk berhasil diperbarui!');
}
}