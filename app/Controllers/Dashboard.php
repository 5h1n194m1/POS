<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        $today = date('Y-m-d');

        $revenueToday = $this->db->table('penjualan')
            ->selectSum('total_harga')
            ->where('DATE(created_at)', $today)
            ->get()
            ->getRowArray();

        $ordersToday = $this->db->table('penjualan')
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $totalProducts = $this->db->table('produk')->countAllResults();

        $lowStockItems = $this->db->table('produk')
            ->where('stok <=', 5)
            ->orderBy('stok', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $dailyRows = $this->db->table('penjualan')
            ->select("DATE(created_at) as tgl, COALESCE(SUM(total_harga), 0) as total", false)
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-6 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('DATE(created_at)', 'ASC')
            ->get()
            ->getResultArray();

        $recentTransactions = $this->db->table('penjualan p')
            ->select('p.id, p.created_at, p.total_harga, u.fullname')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'title'               => 'Dashboard',
            'revenue_today'       => (float) ($revenueToday['total_harga'] ?? 0),
            'total_orders_today'  => $ordersToday,
            'total_products'      => $totalProducts,
            'low_stock_count'     => count($lowStockItems),
            'low_stock_items'     => $lowStockItems,
            'chart_data'          => $this->fillDailySeries($dailyRows),
            'recent_transactions' => $recentTransactions,
        ]);
    }

    public function getNavbarData()
    {
        $lowStockCount = $this->db->table('produk')
            ->where('stok <=', 5)
            ->countAllResults();

        return $this->response->setJSON([
            'nama_user'       => session()->get('fullname') ?: 'User',
            'low_stock_count' => $lowStockCount,
        ]);
    }

    private function fillDailySeries(array $rows): array
    {
        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row['tgl']] = (float) $row['total'];
        }

        $result  = [];
        $current = strtotime('-6 days');
        $end     = strtotime(date('Y-m-d'));

        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $result[] = [
                'tgl'   => date('d M', $current),
                'total' => $mapped[$date] ?? 0,
            ];
            $current = strtotime('+1 day', $current);
        }

        return $result;
    }
}