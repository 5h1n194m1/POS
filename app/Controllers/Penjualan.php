<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\ProductModel;
use Config\Database;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $detailModel;
    protected $productModel;
    protected $memberModel;
    protected $db;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detailModel    = new PenjualanDetailModel();
        $this->productModel   = new ProductModel();
        $this->memberModel    = new MemberModel();
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

        $memberId      = (int) $this->request->getPost('member_id');
        $discountType  = trim((string) $this->request->getPost('diskon_type'));
        $discountInput = (float) $this->request->getPost('diskon_input');
        $bayar         = (float) $this->request->getPost('bayar');

        $member = null;
        if ($memberId > 0) {
            $member = $this->memberModel->find($memberId);
            if (! $member) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Member yang dipilih tidak ditemukan.',
                ]);
            }
        }

        if (! in_array($discountType, ['', 'nominal', 'percent'], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Tipe diskon tidak valid.',
            ]);
        }

        if ($discountInput < 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Nilai diskon tidak boleh negatif.',
            ]);
        }

        if ($member === null && ($discountType !== '' || $discountInput > 0)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Pilih member terlebih dahulu sebelum memberi diskon spesial.',
            ]);
        }

        $validatedCart = [];
        $subtotalKotor = 0;
        $totalModal    = 0;

        foreach ($cart as $id => $item) {
            $product = $this->productModel->find((int) $id);

            if (! $product) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Produk dengan ID ' . $id . ' tidak ditemukan.',
                ]);
            }

            $qty = (int) ($item['qty'] ?? 0);
            if ($qty < 1) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Qty untuk produk "' . $product['nama_produk'] . '" tidak valid.',
                ]);
            }

            if ((int) $product['stok'] < $qty) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Stok produk "' . $product['nama_produk'] . '" tidak mencukupi.',
                ]);
            }

            $hargaJual = (float) $product['harga_jual'];
            $hargaBeli = (float) ($product['harga_beli'] ?? 0);
            $subtotal  = $hargaJual * $qty;

            $validatedCart[] = [
                'product_id'   => (int) $product['id'],
                'nama_produk'  => (string) $product['nama_produk'],
                'kode_produk'  => (string) ($product['kode_produk'] ?? ''),
                'harga_beli'   => $hargaBeli,
                'harga_jual'   => $hargaJual,
                'qty'          => $qty,
                'subtotal'     => $subtotal,
            ];

            $subtotalKotor += $subtotal;
            $totalModal    += $hargaBeli * $qty;
        }

        $diskonNominal = $this->calculateDiscountNominal($subtotalKotor, $discountType, $discountInput);
        $totalHarga    = max(0, $subtotalKotor - $diskonNominal);

        if ($bayar < $totalHarga) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Nominal bayar kurang dari total transaksi.',
            ]);
        }

        $kembalian = $bayar - $totalHarga;
        $createdAt = date('Y-m-d H:i:s');

        $this->db->transBegin();

        try {
            if ($discountType === '' || $diskonNominal <= 0) {
                $discountType  = null;
                $discountInput = 0;
            }

            $dataPenjualan = [
                'user_id'        => session()->get('user_id'),
                'member_id'      => $member['id'] ?? null,
                'member_no'      => $member['no_member'] ?? null,
                'member_nama'    => $member['nama'] ?? null,
                'subtotal_kotor' => $subtotalKotor,
                'diskon_type'    => $discountType,
                'diskon_input'   => $discountInput,
                'diskon_nominal' => $diskonNominal,
                'total_modal'    => $totalModal,
                'total_harga'    => $totalHarga,
                'bayar'          => $bayar,
                'kembalian'      => $kembalian,
                'created_at'     => $createdAt,
            ];

            if (! $this->penjualanModel->insert($dataPenjualan)) {
                throw new \RuntimeException('Gagal menyimpan header transaksi.');
            }

            $penjualanId = $this->penjualanModel->getInsertID();

            foreach ($validatedCart as $item) {
                $insertDetail = $this->detailModel->insert([
                    'penjualan_id' => $penjualanId,
                    'product_id'   => $item['product_id'],
                    'nama_produk'  => $item['nama_produk'],
                    'kode_produk'  => $item['kode_produk'],
                    'harga_beli'   => $item['harga_beli'],
                    'harga_jual'   => $item['harga_jual'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['subtotal'],
                ]);

                if (! $insertDetail) {
                    throw new \RuntimeException('Gagal menyimpan detail transaksi.');
                }

                $updated = $this->productModel
                    ->where('id', $item['product_id'])
                    ->set('stok', 'stok - ' . (int) $item['qty'], false)
                    ->update();

                if (! $updated) {
                    throw new \RuntimeException('Gagal mengurangi stok produk "' . $item['nama_produk'] . '".');
                }
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $this->db->transCommit();
            session()->remove('cart');

            $printUrl80 = base_url('penjualan/print_nota/' . $penjualanId . '?paper=80');
            $printUrl58 = base_url('penjualan/print_nota/' . $penjualanId . '?paper=58');

            return $this->response->setJSON([
                'status'       => 'success',
                'msg'          => 'Transaksi berhasil disimpan.',
                'id'           => $penjualanId,
                'invoice_no'   => $this->formatInvoiceNo($penjualanId, $createdAt),
                'print_url'    => $printUrl80,
                'print_url_80' => $printUrl80,
                'print_url_58' => $printUrl58,
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
            ->select('
                d.qty,
                d.subtotal,
                COALESCE(d.nama_produk, produk.nama_produk) as nama_produk,
                COALESCE(d.kode_produk, produk.kode_produk) as kode_produk,
                COALESCE(d.harga_jual, produk.harga_jual) as harga_jual
            ', false)
            ->join('produk', 'produk.id = d.product_id', 'left')
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

    private function calculateDiscountNominal(float $subtotalKotor, string $discountType, float $discountInput): float
    {
        if ($subtotalKotor <= 0 || $discountType === '' || $discountInput <= 0) {
            return 0;
        }

        if ($discountType === 'percent') {
            $percent = min(100, $discountInput);
            return round($subtotalKotor * ($percent / 100), 2);
        }

        return min($subtotalKotor, $discountInput);
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
