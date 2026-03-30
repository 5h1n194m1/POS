<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Kasir extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        $data['cart'] = session()->get('cart') ?? [];
        $data['title'] = 'Transaksi Kasir';
        
        return view('kasir/index', $data);
    }

    public function addToCart()
    {
        $productModel = new ProductModel();
        $id = $this->request->getPost('product_id');
        $qty = (int)$this->request->getPost('qty');

        $product = $productModel->find($id);

        if ($product) {
            // Cek Stok
            if ($product['stok'] < $qty) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Stok tidak mencukupi! Sisa: ' . $product['stok']
                ]);
            }

            $cart = session()->get('cart') ?? [];
            
            if (isset($cart[$id])) {
                $cart[$id]['qty'] += $qty;
                $cart[$id]['subtotal'] = $cart[$id]['qty'] * $cart[$id]['price'];
            } else {
                $cart[$id] = [
                    'id'    => $product['id'],
                    'name'  => $product['nama_produk'],
                    'price' => $product['harga_jual'],
                    'qty'   => $qty,
                    'subtotal' => $product['harga_jual'] * $qty
                ];
            }

            session()->set('cart', $cart);

            // Hitung Grand Total untuk dikirim balik ke AJAX
            $grandTotal = array_sum(array_column($cart, 'subtotal'));

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Produk berhasil ditambah',
                'cart'   => $cart,
                'grandTotal' => $grandTotal,
                'formattedTotal' => number_format($grandTotal, 0, ',', '.')
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
    }

    public function clearCart()
    {
        session()->remove('cart');
        return redirect()->to('/kasir');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }
        return redirect()->to('/kasir');
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        $bayar = $this->request->getPost('bayar');
        
        if (empty($cart)) {
            return redirect()->to('/kasir')->with('error', 'Keranjang masih kosong!');
        }

        $grandTotal = array_sum(array_column($cart, 'subtotal'));

        if ($bayar < $grandTotal) {
            return redirect()->back()->with('error', 'Uang pembayaran tidak mencukupi!');
        }

        $db = \Config\Database::connect();
        $productModel = new ProductModel();

        $db->transStart();

        $db->table('penjualan')->insert([
            'total_harga' => $grandTotal,
            'bayar'       => $bayar,
            'kembalian'   => $bayar - $grandTotal,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        
        $penjualan_id = $db->insertID();

        foreach ($cart as $id => $item) {
            $db->table('penjualan_detail')->insert([
                'penjualan_id' => $penjualan_id,
                'produk_id'    => $id,
                'qty'          => $item['qty'],
                'subtotal'     => $item['subtotal']
            ]);

            // Update Stok
            $produk = $productModel->find($id);
            $productModel->update($id, ['stok' => $produk['stok'] - $item['qty']]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi.');
        }

        session()->remove('cart');
        return redirect()->to('/kasir')->with('success', 'Transaksi Berhasil!')->with('print_id', $penjualan_id);
    }
}