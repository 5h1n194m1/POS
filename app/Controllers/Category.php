<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use Config\Services;

class Category extends BaseController
{
    protected $categoryModel;
    protected $productModel;
    protected $validation;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel  = new ProductModel();
        $this->validation    = Services::validation();
    }

    public function index()
    {
        return view('category/index', [
            'title' => 'Kategori Produk',
        ]);
    }

    public function listData()
    {
        $db = \Config\Database::connect();

        $categories = $db->table('kategori')
            ->select('kategori.*, COUNT(produk.id) as total_produk')
            ->join('produk', 'produk.kategori_id = kategori.id', 'left')
            ->groupBy('kategori.id')
            ->orderBy('kategori.nama_kategori', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $categories,
        ]);
    }

    public function save()
    {
        $nama = trim((string) $this->request->getPost('nama_kategori'));

        $this->validation->setRules([
            'nama_kategori' => 'required|min_length[2]|max_length[100]|is_unique[kategori.nama_kategori]',
        ]);

        if (! $this->validation->run(['nama_kategori' => $nama])) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => $this->validation->getError('nama_kategori') ?: 'Validasi gagal.',
            ]);
        }

        $this->categoryModel->insert([
            'nama_kategori' => $nama,
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Kategori berhasil ditambahkan.',
        ]);
    }

    public function update()
    {
        $id   = (int) $this->request->getPost('id');
        $nama = trim((string) $this->request->getPost('nama_kategori'));

        $category = $this->categoryModel->find($id);
        if (! $category) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'Kategori tidak ditemukan.',
            ]);
        }

        $this->validation->setRules([
            'nama_kategori' => 'required|min_length[2]|max_length[100]',
        ]);

        if (! $this->validation->run(['nama_kategori' => $nama])) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => $this->validation->getError('nama_kategori') ?: 'Validasi gagal.',
            ]);
        }

        $sameName = $this->categoryModel
            ->where('nama_kategori', $nama)
            ->where('id !=', $id)
            ->first();

        if ($sameName) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Nama kategori sudah digunakan.',
            ]);
        }

        $this->categoryModel->update($id, [
            'nama_kategori' => $nama,
        ]);

        $this->productModel
            ->where('kategori_id', $id)
            ->set('kategori', $nama)
            ->update();

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Kategori berhasil diperbarui.',
        ]);
    }

    public function delete($id)
    {
        $id = (int) $id;

        $category = $this->categoryModel->find($id);
        if (! $category) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'Kategori tidak ditemukan.',
            ]);
        }

        $used = $this->productModel->where('kategori_id', $id)->countAllResults();
        if ($used > 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Kategori tidak bisa dihapus karena masih dipakai produk.',
            ]);
        }

        $this->categoryModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Kategori berhasil dihapus.',
        ]);
    }
}