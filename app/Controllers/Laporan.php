<?php

namespace App\Controllers;

use Config\Database;

class Laporan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        $filter = $this->resolveDateRange();

        return view('laporan/index', [
            'title'         => 'Laporan Penjualan',
            'cashiers'      => $this->getCashiers(),
            'defaultStart'  => $filter['start_date'],
            'defaultEnd'    => $filter['end_date'],
            'defaultPeriod' => $filter['period'],
        ]);
    }

    public function riwayat()
    {
        $filter = $this->resolveDateRange();

        return view('laporan/riwayat', [
            'title'         => 'Riwayat Transaksi',
            'cashiers'      => $this->getCashiers(),
            'defaultStart'  => $filter['start_date'],
            'defaultEnd'    => $filter['end_date'],
            'defaultPeriod' => $filter['period'],
        ]);
    }

    public function summary()
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');

        $builder = $this->db->table('penjualan p');
        $builder->select('COUNT(p.id) as total_transaksi, COALESCE(SUM(p.total_harga), 0) as omzet, COALESCE(AVG(p.total_harga), 0) as rata_transaksi');
        $this->applyFilters($builder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $summary = $builder->get()->getRowArray();

        $itemBuilder = $this->db->table('penjualan_detail d');
        $itemBuilder->select('COALESCE(SUM(d.qty), 0) as total_item');
        $itemBuilder->join('penjualan p', 'p.id = d.penjualan_id');
        $this->applyFilters($itemBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $itemSummary = $itemBuilder->get()->getRowArray();

        return $this->response->setJSON([
            'total_transaksi' => (int) ($summary['total_transaksi'] ?? 0),
            'omzet'           => (float) ($summary['omzet'] ?? 0),
            'rata_transaksi'  => (float) ($summary['rata_transaksi'] ?? 0),
            'total_item'      => (int) ($itemSummary['total_item'] ?? 0),
        ]);
    }

    public function chartData()
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');

        $dailyBuilder = $this->db->table('penjualan p');
        $dailyBuilder->select("DATE(p.created_at) as tanggal, COALESCE(SUM(p.total_harga), 0) as total", false);
        $this->applyFilters($dailyBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $dailyBuilder->groupBy('DATE(p.created_at)');
        $dailyBuilder->orderBy('DATE(p.created_at)', 'ASC');
        $dailyRows = $dailyBuilder->get()->getResultArray();

        $topProductBuilder = $this->db->table('penjualan_detail d');
        $topProductBuilder->select('pr.nama_produk, COALESCE(SUM(d.qty), 0) as qty');
        $topProductBuilder->join('penjualan p', 'p.id = d.penjualan_id');
        $topProductBuilder->join('produk pr', 'pr.id = d.product_id');
        $this->applyFilters($topProductBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $topProductBuilder->groupBy('d.product_id');
        $topProductBuilder->orderBy('qty', 'DESC');
        $topProductBuilder->limit(7);
        $topProducts = $topProductBuilder->get()->getResultArray();

        return $this->response->setJSON([
            'daily_series' => $this->fillDailySeries($filter['start_date'], $filter['end_date'], $dailyRows),
            'top_products' => $topProducts,
        ]);
    }

    public function riwayatData()
    {
        $rows = $this->getTransactionRows();

        return view('laporan/partials/table_riwayat', [
            'rows' => $rows,
        ]);
    }

    public function detail($id)
    {
        $header = $this->db->table('penjualan p')
            ->select('p.*, u.fullname, u.username, u.email')
            ->join('users u', 'u.id = p.user_id')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (! $header) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data transaksi tidak ditemukan.',
            ]);
        }

        $items = $this->db->table('penjualan_detail d')
            ->select('d.qty, d.subtotal, pr.nama_produk, pr.kode_produk, pr.harga_jual')
            ->join('produk pr', 'pr.id = d.product_id')
            ->where('d.penjualan_id', $id)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'     => 'success',
            'invoice_no' => $this->formatInvoiceNo((int) $header['id'], $header['created_at']),
            'header'     => $header,
            'items'      => $items,
        ]);
    }

    public function exportCsv()
    {
        $rows = $this->getTransactionRows();

        $filename = 'laporan_penjualan_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'Invoice',
            'Tanggal',
            'Kasir',
            'Total Item',
            'Total Harga',
            'Bayar',
            'Kembalian'
        ]);

        foreach ($rows as $row) {
            fputcsv($output, [
                $this->formatInvoiceNo((int) $row['id'], $row['created_at']),
                $row['created_at'],
                $row['fullname'],
                $row['total_item'],
                $row['total_harga'],
                $row['bayar'],
                $row['kembalian'],
            ]);
        }

        fclose($output);
        exit;
    }

    public function printReport()
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');
        $rows      = $this->getTransactionRows();

        $summaryBuilder = $this->db->table('penjualan p');
        $summaryBuilder->select('COUNT(p.id) as total_transaksi, COALESCE(SUM(p.total_harga), 0) as omzet, COALESCE(AVG(p.total_harga), 0) as rata_transaksi');
        $this->applyFilters($summaryBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $summary = $summaryBuilder->get()->getRowArray();

        $itemBuilder = $this->db->table('penjualan_detail d');
        $itemBuilder->select('COALESCE(SUM(d.qty), 0) as total_item');
        $itemBuilder->join('penjualan p', 'p.id = d.penjualan_id');
        $this->applyFilters($itemBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $itemSummary = $itemBuilder->get()->getRowArray();

        $cashierName = 'Semua Kasir';
        if (! empty($cashierId)) {
            $cashier = $this->db->table('users')->where('id', $cashierId)->get()->getRowArray();
            if ($cashier) {
                $cashierName = $cashier['fullname'];
            }
        }

        return view('laporan/print', [
            'title'            => 'Print Laporan Penjualan',
            'rows'             => $rows,
            'summary'          => $summary,
            'total_item'       => (int) ($itemSummary['total_item'] ?? 0),
            'start_date'       => $filter['start_date'],
            'end_date'         => $filter['end_date'],
            'printed_at'       => date('d M Y H:i:s'),
            'cashier_name'     => $cashierName,
            'store_name'       => 'POS SAYA',
            'store_subtitle'   => 'Sistem Informasi Kasir',
            'report_generated_by' => session()->get('fullname') ?: 'Admin',
        ]);
    }

    private function getTransactionRows(): array
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');
        $keyword   = trim((string) $this->request->getGet('keyword'));

        $builder = $this->db->table('penjualan p');
        $builder->select('p.id, p.created_at, p.total_harga, p.bayar, p.kembalian, u.fullname, COALESCE(SUM(d.qty), 0) as total_item');
        $builder->join('users u', 'u.id = p.user_id');
        $builder->join('penjualan_detail d', 'd.penjualan_id = p.id', 'left');

        $this->applyFilters($builder, $filter, 'p.created_at', 'p.user_id', $cashierId);

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('u.fullname', $keyword)
                ->orLike('p.id', $keyword)
            ->groupEnd();
        }

        return $builder->groupBy('p.id')
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function getCashiers(): array
    {
        return $this->db->table('users')
            ->select('id, fullname')
            ->where('status', 'aktif')
            ->orderBy('fullname', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function resolveDateRange(): array
    {
        $period    = $this->request->getGet('period') ?: 'last_7_days';
        $today     = date('Y-m-d');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        switch ($period) {
            case 'today':
                $startDate = $today;
                $endDate   = $today;
                break;

            case 'last_7_days':
                $startDate = date('Y-m-d', strtotime('-6 days'));
                $endDate   = $today;
                break;

            case 'this_month':
                $startDate = date('Y-m-01');
                $endDate   = $today;
                break;

            case 'this_year':
                $startDate = date('Y-01-01');
                $endDate   = $today;
                break;

            case 'custom':
                $startDate = $startDate ?: date('Y-m-d', strtotime('-6 days'));
                $endDate   = $endDate ?: $today;
                break;

            default:
                $period    = 'last_7_days';
                $startDate = date('Y-m-d', strtotime('-6 days'));
                $endDate   = $today;
                break;
        }

        return [
            'period'     => $period,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];
    }

    private function applyFilters($builder, array $filter, string $dateColumn, string $cashierColumn, $cashierId = null): void
    {
        $builder->where($dateColumn . ' >=', $filter['start_date'] . ' 00:00:00');
        $builder->where($dateColumn . ' <=', $filter['end_date'] . ' 23:59:59');

        if (! empty($cashierId)) {
            $builder->where($cashierColumn, $cashierId);
        }
    }

    private function fillDailySeries(string $startDate, string $endDate, array $rows): array
    {
        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row['tanggal']] = (float) $row['total'];
        }

        $result  = [];
        $current = strtotime($startDate);
        $end     = strtotime($endDate);

        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $result[] = [
                'label' => date('d M', $current),
                'date'  => $date,
                'total' => $mapped[$date] ?? 0,
            ];
            $current = strtotime('+1 day', $current);
        }

        return $result;
    }

    private function formatInvoiceNo(int $id, ?string $createdAt = null): string
    {
        $date = $createdAt ? date('Ymd', strtotime($createdAt)) : date('Ymd');
        return 'INV-' . $date . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }
}