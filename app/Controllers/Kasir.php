<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\ProductModel;

class Kasir extends BaseController
{
    protected $productModel;
    protected $memberModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->memberModel  = new MemberModel();
    }

    public function index()
    {
        $products = $this->productModel
            ->orderBy('nama_produk', 'ASC')
            ->findAll();

        return view('kasir/index', [
            'title'    => 'Transaksi Kasir',
            'products' => $products,
            'members'  => $this->memberModel->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function addToCart()
    {
        $productId = (int) $this->request->getPost('product_id');
        $qty       = max(1, (int) $this->request->getPost('qty'));

        $product = $this->productModel->find($productId);

        if (! $product) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan.',
            ]);
        }

        $cart = session()->get('cart') ?? [];
        $existingQty = isset($cart[$productId]) ? (int) $cart[$productId]['qty'] : 0;
        $requestedQty = $existingQty + $qty;

        if ($requestedQty > (int) $product['stok']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Stok tidak mencukupi untuk produk ' . $product['nama_produk'] . '.',
            ]);
        }

        $cart[$productId] = [
            'id'       => $product['id'],
            'name'     => $product['nama_produk'],
            'price'    => (float) $product['harga_jual'],
            'qty'      => $requestedQty,
            'subtotal' => (float) $product['harga_jual'] * $requestedQty,
        ];

        session()->set('cart', $cart);

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Produk berhasil ditambahkan ke keranjang.',
            'cart'       => $cart,
            'grandTotal' => $this->grandTotal($cart),
        ]);
    }

    public function remove($id)
    {
        $id = (int) $id;
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'cart'   => $cart,
            'total'  => $this->grandTotal($cart),
        ]);
    }

    public function clearCart()
    {
        session()->remove('cart');
        return redirect()->to('/kasir')->with('msg', 'Keranjang berhasil dibersihkan.');
    }

    public function checkout()
    {
        return redirect()->to('/kasir');
    }

    private function grandTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += (float) $item['subtotal'];
        }
        return $total;
    }
}
