<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Config\Services;

class Product extends BaseController
{
    protected $productModel;
    protected $validation;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->validation   = Services::validation();
    }

    public function index()
    {
        return view('product/index', [
            'title' => 'Data Produk',
        ]);
    }

    public function listData()
    {
        $products = $this->productModel
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'data' => $products,
        ]);
    }

    public function save()
    {
        $data = [
            'nama_produk' => trim((string) $this->request->getPost('nama_produk')),
            'kode_produk' => trim((string) $this->request->getPost('kode_produk')),
            'kategori'    => trim((string) $this->request->getPost('kategori')),
            'harga_beli'  => (float) $this->request->getPost('harga_beli'),
            'harga_jual'  => (float) $this->request->getPost('harga_jual'),
            'stok'        => (int) $this->request->getPost('stok'),
        ];

        $this->validation->setRules([
            'nama_produk' => 'required|min_length[2]|max_length[255]',
            'kode_produk' => 'permit_empty|max_length[50]',
            'kategori'    => 'permit_empty|max_length[100]',
            'harga_beli'  => 'required|decimal',
            'harga_jual'  => 'required|decimal',
            'stok'        => 'required|integer',
        ]);

        if (! $this->validation->run($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Validasi gagal.',
                'errors' => $this->validation->getErrors(),
            ]);
        }

        if ($data['kode_produk'] !== '') {
            $sameCode = $this->productModel->where('kode_produk', $data['kode_produk'])->first();
            if ($sameCode) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Kode produk sudah digunakan.',
                ]);
            }
        }

        $this->productModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Produk berhasil ditambahkan.',
        ]);
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id');
        $product = $this->productModel->find($id);

        if (! $product) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'Produk tidak ditemukan.',
            ]);
        }

        $data = [
            'nama_produk' => trim((string) $this->request->getPost('nama_produk')),
            'kode_produk' => trim((string) $this->request->getPost('kode_produk')),
            'kategori'    => trim((string) $this->request->getPost('kategori')),
            'harga_beli'  => (float) $this->request->getPost('harga_beli'),
            'harga_jual'  => (float) $this->request->getPost('harga_jual'),
            'stok'        => (int) $this->request->getPost('stok'),
        ];

        $this->validation->setRules([
            'nama_produk' => 'required|min_length[2]|max_length[255]',
            'kode_produk' => 'permit_empty|max_length[50]',
            'kategori'    => 'permit_empty|max_length[100]',
            'harga_beli'  => 'required|decimal',
            'harga_jual'  => 'required|decimal',
            'stok'        => 'required|integer',
        ]);

        if (! $this->validation->run($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Validasi gagal.',
                'errors' => $this->validation->getErrors(),
            ]);
        }

        if ($data['kode_produk'] !== '') {
            $sameCode = $this->productModel
                ->where('kode_produk', $data['kode_produk'])
                ->where('id !=', $id)
                ->first();

            if ($sameCode) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Kode produk sudah digunakan.',
                ]);
            }
        }

        $this->productModel->update($id, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Produk berhasil diperbarui.',
        ]);
    }

    public function delete($id)
    {
        $id = (int) $id;
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        try {
            $this->productModel->delete($id);
            return redirect()->to('/product')->with('msg', 'Produk berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->to('/product')->with('error', 'Produk tidak bisa dihapus karena sudah dipakai transaksi.');
        }
    }
}