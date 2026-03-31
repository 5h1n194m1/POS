<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Tambahkan kolom baru agar bisa disimpan ke database
    protected $allowedFields    = [
        'username', 
        'fullname', 
        'email', 
        'password', 
        'role', 
        'status', 
        'last_login'
    ];

    // Dates - Mengaktifkan fitur otomatis created_at & updated_at
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation - Opsional: Bisa diisi nanti jika ingin validasi otomatis
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks - Berguna jika ingin enkripsi password otomatis di model (nanti saja)
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
}