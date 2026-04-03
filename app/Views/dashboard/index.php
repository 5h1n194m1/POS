<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$isAdmin = (session()->get('role') === 'admin');

$quickMenus = [
    [
        'label' => 'Transaksi Baru',
        'icon'  => 'fas fa-cash-register',
        'url'   => base_url('kasir'),
        'color' => 'primary',
    ],
    [
        'label' => 'Profil Saya',
        'icon'  => 'fas fa-user-circle',
        'url'   => base_url('profile'),
        'color' => 'secondary',
    ],
];

if ($isAdmin) {
    $quickMenus = [
        [
            'label' => 'Transaksi Baru',
            'icon'  => 'fas fa-cash-register',
            'url'   => base_url('kasir'),
            'color' => 'primary',
        ],
        [
            'label' => 'Data Barang',
            'icon'  => 'fas fa-boxes',
            'url'   => base_url('product'),
            'color' => 'success',
        ],
        [
            'label' => 'Riwayat',
            'icon'  => 'fas fa-history',
            'url'   => base_url('riwayat-transaksi'),
            'color' => 'warning',
        ],
        [
            'label' => 'Laporan',
            'icon'  => 'fas fa-chart-line',
            'url'   => base_url('laporan-penjualan'),
            'color' => 'danger',
        ],
        [
            'label' => 'Karyawan',
            'icon'  => 'fas fa-users',
            'url'   => base_url('user'),
            'color' => 'info',
        ],
        [
            'label' => 'Profil Saya',
            'icon'  => 'fas fa-user-circle',
            'url'   => base_url('profile'),
            'color' => 'dark',
        ],
    ];
}
?>

<style>
    .dashboard-hero {
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
    }

    .dashboard-hero .hero-title {
        font-size: 1.45rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .dashboard-hero .hero-subtitle {
        font-size: .95rem;
        opacity: .95;
        margin-bottom: 0;
    }

    .quick-menu-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .quick-menu-card {
        display: block;
        text-decoration: none !important;
        border-radius: 16px;
        padding: 16px 14px;
        color: #111827;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        transition: .15s ease-in-out;
        min-height: 108px;
    }

    .quick-menu-card:hover {
        transform: translateY(-2px);
        color: #111827;
    }

    .quick-menu-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .quick-menu-label {
        font-weight: 700;
        font-size: 15px;
        line-height: 1.2;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .stat-card {
        border-radius: 16px;
        padding: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111827;
    }

    .activity-list .list-group-item {
        border-left: 0;
        border-right: 0;
        padding-left: 0;
        padding-right: 0;
    }

    .mini-muted {
        font-size: 12px;
        color: #6b7280;
    }

    body.dark-mode .quick-menu-card,
    body.dark-mode .stat-card,
    body.dark-mode .dashboard-section-card {
        background: #1f2937 !important;
        border-color: #374151 !important;
        color: #f9fafb !important;
    }

    body.dark-mode .quick-menu-label,
    body.dark-mode .stat-value,
    body.dark-mode .section-title {
        color: #f9fafb !important;
    }

    body.dark-mode .mini-muted,
    body.dark-mode .stat-label {
        color: #9ca3af !important;
    }

    @media (min-width: 992px) {
        .quick-menu-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .stats-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
</style>

<div class="mb-3">
    <div class="dashboard-hero">
        <div class="hero-title">Halo, <?= esc(session()->get('fullname') ?: 'User') ?></div>
        <p class="hero-subtitle mb-2">
            Anda login sebagai <strong><?= strtoupper(esc(session()->get('role') ?: 'guest')) ?></strong>.
        </p>
        <small><?= date('d F Y') ?></small>
    </div>
</div>

<div class="mb-4">
    <div class="section-title">Menu Cepat</div>
    <div class="quick-menu-grid">
        <?php foreach ($quickMenus as $menu): ?>
            <a href="<?= $menu['url'] ?>" class="quick-menu-card">
                <div class="quick-menu-icon bg-<?= $menu['color'] ?>">
                    <i class="<?= esc($menu['icon']) ?>"></i>
                </div>
                <div class="quick-menu-label"><?= esc($menu['label']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="mb-4">
    <div class="section-title">Ringkasan Hari Ini</div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Omzet Hari Ini</div>
            <div class="stat-value">Rp <?= number_format($revenue_today ?? 0, 0, ',', '.') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= number_format($total_orders_today ?? 0, 0, ',', '.') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Barang</div>
            <div class="stat-value"><?= number_format($total_products ?? 0, 0, ',', '.') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Stok Menipis</div>
            <div class="stat-value"><?= number_format($low_stock_count ?? 0, 0, ',', '.') ?></div>
        </div>
    </div>
</div>

<div class="card dashboard-section-card mb-4">
    <div class="card-header border-0">
        <div class="section-title mb-0">Tren Penjualan 7 Hari Terakhir</div>
    </div>
    <div class="card-body">
        <canvas id="dailyChart" style="min-height: 260px; height: 260px;"></canvas>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card dashboard-section-card mb-4">
            <div class="card-header border-0">
                <div class="section-title mb-0">Transaksi Terbaru</div>
            </div>
            <div class="card-body pt-0">
                <div class="list-group list-group-flush activity-list">
                    <?php if (!empty($recent_transactions)): ?>
                        <?php foreach ($recent_transactions as $trx): ?>
                            <div class="list-group-item bg-transparent">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="font-weight-bold">
                                            INV-<?= date('Ymd', strtotime($trx['created_at'])) ?>-<?= str_pad((string) $trx['id'], 5, '0', STR_PAD_LEFT) ?>
                                        </div>
                                        <div class="mini-muted"><?= esc($trx['fullname'] ?? '-') ?></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-weight-bold text-primary">
                                            Rp <?= number_format((float) $trx['total_harga'], 0, ',', '.') ?>
                                        </div>
                                        <div class="mini-muted"><?= date('d M H:i', strtotime($trx['created_at'])) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item bg-transparent text-center mini-muted py-4">
                            Belum ada transaksi terbaru.
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($isAdmin): ?>
                    <div class="mt-3">
                        <a href="<?= base_url('riwayat-transaksi') ?>" class="btn btn-outline-primary btn-block">
                            Lihat Semua Riwayat
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card dashboard-section-card mb-4">
            <div class="card-header border-0">
                <div class="section-title mb-0">Stok Menipis</div>
            </div>
            <div class="card-body pt-0">
                <div class="list-group list-group-flush activity-list">
                    <?php if (!empty($low_stock_items)): ?>
                        <?php foreach ($low_stock_items as $item): ?>
                            <div class="list-group-item bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="font-weight-bold"><?= esc($item['nama_produk']) ?></div>
                                        <div class="mini-muted"><?= esc($item['kode_produk'] ?? '-') ?></div>
                                    </div>
                                    <span class="badge badge-danger px-3 py-2"><?= (int) $item['stok'] ?> unit</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item bg-transparent text-center mini-muted py-4">
                            Semua stok masih aman.
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($isAdmin): ?>
                    <div class="mt-3">
                        <a href="<?= base_url('product') ?>" class="btn btn-outline-secondary btn-block">
                            Buka Data Barang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const dailyData = <?= json_encode($chart_data) ?>;
        Chart.defaults.color = $('body').hasClass('dark-mode') ? '#d1d5db' : '#6b7280';
        Chart.defaults.font.family = "'Source Sans Pro', sans-serif";

        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyData.map(item => item.tgl),
                datasets: [{
                    label: 'Omzet',
                    data: dailyData.map(item => item.total),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>