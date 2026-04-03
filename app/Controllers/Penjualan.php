<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\ProductModel;
use Config\Database;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $detailModel;
    protected $productModel;
    protected $db;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detailModel    = new PenjualanDetailModel();
        $this->productModel   = new ProductModel();
        $this->db             = Database::connect();
    }

    public function save()
    {
        $cart = session()->get('cart') ?? [];

        if (empty($cart)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg'    => 'Keranjang kosong.',
            ]);
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += (float) $item['subtotal'];
        }

        $bayar = (float) $this->request->getPost('bayar');
        if ($bayar < $grandTotal) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Nominal bayar kurang dari total transaksi.',
            ]);
        }

        $kembalian = $bayar - $grandTotal;
        $createdAt = date('Y-m-d H:i:s');

        $this->db->transBegin();

        try {
            $dataPenjualan = [
                'user_id'     => session()->get('user_id'),
                'total_harga' => $grandTotal,
                'bayar'       => $bayar,
                'kembalian'   => $kembalian,
                'created_at'  => $createdAt,
            ];

            if (! $this->penjualanModel->insert($dataPenjualan)) {
                throw new \Exception('Gagal menyimpan header transaksi.');
            }

            $penjualanId = $this->penjualanModel->getInsertID();

            foreach ($cart as $id => $item) {
                $product = $this->productModel->find($id);

                if (! $product) {
                    throw new \Exception('Produk dengan ID ' . $id . ' tidak ditemukan.');
                }

                if ((int) $product['stok'] < (int) $item['qty']) {
                    throw new \Exception('Stok produk "' . $product['nama_produk'] . '" tidak mencukupi.');
                }

                $insertDetail = $this->detailModel->insert([
                    'penjualan_id' => $penjualanId,
                    'product_id'   => $id,
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['subtotal'],
                ]);

                if (! $insertDetail) {
                    throw new \Exception('Gagal menyimpan detail transaksi.');
                }

                $updated = $this->productModel
                    ->where('id', $id)
                    ->set('stok', 'stok - ' . (int) $item['qty'], false)
                    ->update();

                if (! $updated) {
                    throw new \Exception('Gagal mengurangi stok produk "' . $product['nama_produk'] . '".');
                }
            }

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal.');
            }

            $this->db->transCommit();
            session()->remove('cart');

            return $this->response->setJSON([
                'status'      => 'success',
                'msg'         => 'Transaksi berhasil disimpan.',
                'id'          => $penjualanId,
                'invoice_no'  => $this->formatInvoiceNo($penjualanId, $createdAt),
                'print_url_80'=> base_url('penjualan/print_nota/' . $penjualanId . '?paper=80'),
                'print_url_58'=> base_url('penjualan/print_nota/' . $penjualanId . '?paper=58'),
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'msg'    => $e->getMessage(),
            ]);
        }
    }

    public function print_nota($id)
    {
        $paper = $this->normalizePaper((string) $this->request->getGet('paper'));

        $builder = $this->db->table('penjualan p');
        $builder->select('p.*, u.fullname, u.username');
        $builder->join('users u', 'u.id = p.user_id', 'left');
        $builder->where('p.id', $id);
        $penjualan = $builder->get()->getRowArray();

        if (! $penjualan) {
            return 'Data transaksi tidak ditemukan.';
        }

        $details = $this->db->table('penjualan_detail d')
            ->select('d.qty, d.subtotal, produk.nama_produk, produk.kode_produk, produk.harga_jual')
            ->join('produk', 'produk.id = d.product_id')
            ->where('d.penjualan_id', $id)
            ->get()
            ->getResultArray();

        return view('kasir/nota', [
            'penjualan'   => $penjualan,
            'details'     => $details,
            'invoice_no'  => $this->formatInvoiceNo((int) $penjualan['id'], $penjualan['created_at']),
            'paper_width' => $paper,
            'store_name'  => 'POS SAYA',
            'store_info'  => 'Sistem Informasi Kasir',
            'store_addr'  => 'Jl. Contoh Alamat Toko',
            'store_phone' => '08xxxxxxxxxx',
        ]);
    }

    private function normalizePaper(string $paper): string
    {
        return in_array($paper, ['58', '80'], true) ? $paper : '80';
    }

    private function formatInvoiceNo(int $id, ?string $createdAt = null): string
    {
        $date = $createdAt ? date('Ymd', strtotime($createdAt)) : date('Ymd');
        return 'INV-' . $date . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }
}