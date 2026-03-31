<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Proteksi Login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 2. Persiapan Data untuk View
        $data = [
            'title'              => 'Dashboard POS | Management System',
            'user_name'          => session()->get('nama_user'),
            
            // Statistik (Tetap di-load awal agar angka utama langsung muncul)
            'total_products'     => $db->table('produk')->countAll(),
            'low_stock_count'    => $db->table('produk')->where('stok <=', 5)->countAllResults(),
            
            // Ambil 5 barang dengan stok terendah
            'low_stock_items'    => $db->table('produk')
                                      ->where('stok <=', 5)
                                      ->orderBy('stok', 'ASC')
                                      ->limit(5)
                                      ->get()->getResultArray(),
            
            // Omzet & Transaksi Hari Ini
            'revenue_today'      => $db->table('penjualan')
                                      ->selectSum('total_harga')
                                      ->where('DATE(created_at)', $today)
                                      ->get()->getRow()->total_harga ?? 0,
                                      
            'total_orders_today' => $db->table('penjualan')
                                      ->where('DATE(created_at)', $today)
                                      ->countAllResults(),

            // Data Grafik
            'chart_data'         => $this->_getSalesChartData($db),
            'chart_monthly'      => $this->_getMonthlySalesData($db),
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Endpoint API untuk Navbar (AJAX)
     * Untuk mempercepat loading navbar di semua halaman
     */
    public function getNavbarData()
    {
        $db = \Config\Database::connect();
        $low_stock = $db->table('produk')->where('stok <=', 5)->countAllResults();

        return $this->response->setJSON([
            'nama_user'       => session()->get('nama_user'),
            'low_stock_count' => $low_stock,
            'server_time'     => date('H:i:s')
        ]);
    }

    private function _getSalesChartData($db)
    {
        return $db->table('penjualan')
                  ->select("DATE(created_at) as tgl, SUM(total_harga) as total")
                  ->where('created_at >=', date('Y-m-d', strtotime('-7 days')))
                  ->groupBy('tgl')
                  ->orderBy('tgl', 'ASC')
                  ->get()->getResultArray();
    }

    private function _getMonthlySalesData($db)
    {
        return $db->table('penjualan')
              ->select("DATE_FORMAT(created_at, '%Y-%m') as periode, DATE_FORMAT(created_at, '%M %Y') as bulan, SUM(total_harga) as total")
              ->where('created_at >=', date('Y-m-d', strtotime('-12 months')))
              ->groupBy('periode, bulan') // Kelompokkan berdasarkan periode juga
              ->orderBy('periode', 'ASC')   // Urutkan berdasarkan string YYYY-MM agar urutan bulannya benar
              ->get()->getResultArray();
    }
}