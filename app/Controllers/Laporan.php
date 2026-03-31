<?php

namespace App\Controllers;

use App\Models\PenjualanModel;

class Laporan extends BaseController
{
    protected $penjualanModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
    }

    public function index()
    {
        // Ambil filter dari URL (jika ada)
        $filter = $this->request->getGet('filter') ?? 'semua';
        $db     = \Config\Database::connect();
        $builder = $db->table('penjualan');
        $builder->select('penjualan.*, users.fullname');
        $builder->join('users', 'users.id = penjualan.user_id');

        // Logika Filter Waktu
        if ($filter == 'hari_ini') {
            $builder->where('DATE(penjualan.created_at)', date('Y-m-d'));
        } elseif ($filter == 'bulan_ini') {
            $builder->where('MONTH(penjualan.created_at)', date('m'));
            $builder->where('YEAR(penjualan.created_at)', date('Y'));
        } elseif ($filter == 'tahun_ini') {
            $builder->where('YEAR(penjualan.created_at)', date('Y'));
        }

        $data = [
            'title'     => 'Riwayat Penjualan',
            'penjualan' => $builder->orderBy('penjualan.created_at', 'DESC')->get()->getResultArray(),
            'filter'    => $filter
        ];

        return view('laporan/index', $data);
    }
}