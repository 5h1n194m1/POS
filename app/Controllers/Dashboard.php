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
            
            // Statistik
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
                                      ->selectSum('total_bayar')
                                      ->where('DATE(tanggal_jual)', $today)
                                      ->get()->getRow()->total_bayar ?? 0,
                                      
            'total_orders_today' => $db->table('penjualan')
                                      ->where('DATE(tanggal_jual)', $today)
                                      ->countAllResults(),

            // Data Grafik Mingguan & Bulanan (Tambahan Baru)
            'chart_data'         => $this->_getSalesChartData($db),
            'chart_monthly'      => $this->_getMonthlySalesData($db),
            
            // Transaksi Terakhir
            'recent_sales'       => $db->table('penjualan')
                                      ->select('penjualan.*, pelanggan.nama_pelanggan')
                                      ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
                                      ->orderBy('tanggal_jual', 'DESC')
                                      ->limit(5)
                                      ->get()->getResultArray(),
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Data Penjualan 7 Hari Terakhir
     */
    private function _getSalesChartData($db)
    {
        return $db->table('penjualan')
                  ->select("DATE(tanggal_jual) as tgl, SUM(total_bayar) as total")
                  ->where('tanggal_jual >=', date('Y-m-d', strtotime('-7 days')))
                  ->groupBy('tgl')
                  ->orderBy('tgl', 'ASC')
                  ->get()->getResultArray();
    }

    /**
     * Data Penjualan Bulanan (12 Bulan Terakhir)
     */
    private function _getMonthlySalesData($db)
    {
        return $db->table('penjualan')
                  ->select("DATE_FORMAT(tanggal_jual, '%Y-%m') as bulan, SUM(total_bayar) as total")
                  ->where('tanggal_jual >=', date('Y-m-d', strtotime('-12 months')))
                  ->groupBy('bulan')
                  ->orderBy('bulan', 'ASC')
                  ->get()->getResultArray();
    }
}