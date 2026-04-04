<?php
namespace App\Models;
use CodeIgniter\Model;

class PenjualanDetailModel extends Model {
    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'penjualan_id',
        'product_id',
        'nama_produk',
        'kode_produk',
        'harga_beli',
        'harga_jual',
        'qty',
        'subtotal',
    ];
    protected $useTimestamps = false; // Wajib false
}
