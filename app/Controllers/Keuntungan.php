<?php

namespace App\Controllers;

use Config\Database;

class Keuntungan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        $filter = $this->resolveDateRange();

        return view('keuntungan/index', [
            'title'         => 'Laporan Keuntungan',
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
        $builder->select('
            COUNT(p.id) as total_transaksi,
            COALESCE(SUM(p.subtotal_kotor), 0) as omzet_kotor,
            COALESCE(SUM(p.diskon_nominal), 0) as total_diskon,
            COALESCE(SUM(p.total_modal), 0) as total_modal,
            COALESCE(SUM(p.total_harga - p.total_modal), 0) as laba_bersih
        ');
        $this->applyFilters($builder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $summary = $builder->get()->getRowArray();

        return $this->response->setJSON([
            'total_transaksi' => (int) ($summary['total_transaksi'] ?? 0),
            'omzet_kotor'     => (float) ($summary['omzet_kotor'] ?? 0),
            'total_diskon'    => (float) ($summary['total_diskon'] ?? 0),
            'total_modal'     => (float) ($summary['total_modal'] ?? 0),
            'laba_bersih'     => (float) ($summary['laba_bersih'] ?? 0),
        ]);
    }

    public function chartData()
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');

        $builder = $this->db->table('penjualan p');
        $builder->select("
            DATE(p.created_at) as tanggal,
            COALESCE(SUM(p.subtotal_kotor), 0) as omzet_kotor,
            COALESCE(SUM(p.total_harga - p.total_modal), 0) as laba_bersih,
            COALESCE(SUM(p.diskon_nominal), 0) as total_diskon
        ", false);
        $this->applyFilters($builder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $builder->groupBy('DATE(p.created_at)');
        $builder->orderBy('DATE(p.created_at)', 'ASC');
        $rows = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'daily_series' => $this->fillDailySeries($filter['start_date'], $filter['end_date'], $rows),
        ]);
    }

    public function tableData()
    {
        return view('keuntungan/partials/table', [
            'rows' => $this->getProfitRows(),
        ]);
    }

    public function exportCsv()
    {
        $rows = $this->getProfitRows();
        $filename = 'laporan_keuntungan_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'Invoice',
            'Tanggal',
            'Kasir',
            'Member',
            'Subtotal Kotor',
            'Diskon',
            'Modal',
            'Total Jual',
            'Laba Bersih',
        ]);

        foreach ($rows as $row) {
            fputcsv($output, [
                $this->formatInvoiceNo((int) $row['id'], $row['created_at']),
                $row['created_at'],
                $row['fullname'],
                $row['member_nama'] ?: '-',
                $row['subtotal_kotor'],
                $row['diskon_nominal'],
                $row['total_modal'],
                $row['total_harga'],
                $row['laba_bersih'],
            ]);
        }

        fclose($output);
        exit;
    }

    public function printReport()
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');
        $rows      = $this->getProfitRows();

        $summaryBuilder = $this->db->table('penjualan p');
        $summaryBuilder->select('
            COUNT(p.id) as total_transaksi,
            COALESCE(SUM(p.subtotal_kotor), 0) as omzet_kotor,
            COALESCE(SUM(p.diskon_nominal), 0) as total_diskon,
            COALESCE(SUM(p.total_modal), 0) as total_modal,
            COALESCE(SUM(p.total_harga - p.total_modal), 0) as laba_bersih
        ');
        $this->applyFilters($summaryBuilder, $filter, 'p.created_at', 'p.user_id', $cashierId);
        $summary = $summaryBuilder->get()->getRowArray();

        $cashierName = 'Semua Kasir';
        if (! empty($cashierId)) {
            $cashier = $this->db->table('users')->where('id', $cashierId)->get()->getRowArray();
            if ($cashier) {
                $cashierName = $cashier['fullname'];
            }
        }

        return view('keuntungan/print', [
            'title'               => 'Print Laporan Keuntungan',
            'rows'                => $rows,
            'summary'             => $summary,
            'start_date'          => $filter['start_date'],
            'end_date'            => $filter['end_date'],
            'printed_at'          => date('d M Y H:i:s'),
            'cashier_name'        => $cashierName,
            'store_name'          => 'POS SAYA',
            'store_subtitle'      => 'Sistem Informasi Kasir',
            'report_generated_by' => session()->get('fullname') ?: 'Admin',
        ]);
    }

    private function getProfitRows(): array
    {
        $filter    = $this->resolveDateRange();
        $cashierId = $this->request->getGet('cashier_id');
        $keyword   = trim((string) $this->request->getGet('keyword'));

        $builder = $this->db->table('penjualan p');
        $builder->select('
            p.id,
            p.created_at,
            p.member_nama,
            p.subtotal_kotor,
            p.diskon_nominal,
            p.total_modal,
            p.total_harga,
            (p.total_harga - p.total_modal) as laba_bersih,
            u.fullname
        ');
        $builder->join('users u', 'u.id = p.user_id', 'left');

        $this->applyFilters($builder, $filter, 'p.created_at', 'p.user_id', $cashierId);

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('u.fullname', $keyword)
                ->orLike('p.member_nama', $keyword)
                ->orLike('p.id', $keyword)
            ->groupEnd();
        }

        return $builder
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
            $mapped[$row['tanggal']] = [
                'omzet_kotor'  => (float) ($row['omzet_kotor'] ?? 0),
                'laba_bersih'  => (float) ($row['laba_bersih'] ?? 0),
                'total_diskon' => (float) ($row['total_diskon'] ?? 0),
            ];
        }

        $result  = [];
        $current = strtotime($startDate);
        $end     = strtotime($endDate);

        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $result[] = [
                'label'        => date('d M', $current),
                'date'         => $date,
                'omzet_kotor'  => $mapped[$date]['omzet_kotor'] ?? 0,
                'laba_bersih'  => $mapped[$date]['laba_bersih'] ?? 0,
                'total_diskon' => $mapped[$date]['total_diskon'] ?? 0,
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
