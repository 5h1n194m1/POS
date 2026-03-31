<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Pastikan semua kolom yang mau diisi ada di sini
    protected $allowedFields    = ['user_id', 'total_harga', 'bayar', 'kembalian', 'created_at'];

    // MATIKAN INI karena kolom updated_at tidak ada di database Anda
    protected $useTimestamps = false; 

    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}