<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Laporan Penjualan</h1>
                <small class="text-muted">Analitik omzet, produk terlaris, dan ekspor laporan.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="<?= base_url('riwayat-transaksi') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-history mr-1"></i> Buka Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter Laporan</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Periode</label>
                <select id="filter-period" class="form-control">
                    <option value="today" <?= $defaultPeriod === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                    <option value="last_7_days" <?= $defaultPeriod === 'last_7_days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="this_month" <?= $defaultPeriod === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="this_year" <?= $defaultPeriod === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                    <option value="custom" <?= $defaultPeriod === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                </select>
            </div>

            <div class="col-md-3 mb-3" id="filter-start-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Mulai</label>
                <input type="date" id="filter-start-date" class="form-control" value="<?= esc($defaultStart) ?>">
            </div>

            <div class="col-md-3 mb-3" id="filter-end-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Selesai</label>
                <input type="date" id="filter-end-date" class="form-control" value="<?= esc($defaultEnd) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Kasir</label>
                <select id="filter-cashier" class="form-control select2">
                    <option value="">Semua Kasir</option>
                    <?php foreach ($cashiers as $cashier): ?>
                        <option value="<?= $cashier['id'] ?>"><?= esc($cashier['fullname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between flex-wrap">
            <div class="mb-2">
                <button type="button" id="btn-export-report" class="btn btn-success mr-2">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </button>
                <button type="button" id="btn-print-report" class="btn btn-dark">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>

            <div class="mb-2">
                <button type="button" id="btn-reset-report" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
                <button type="button" id="btn-apply-report" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row" id="report-summary-cards">
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-info shadow-sm">
            <div class="inner"><h3>...</h3><p>Omzet</p></div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-success shadow-sm">
            <div class="inner"><h3>...</h3><p>Total Transaksi</p></div>
            <div class="icon"><i class="fas fa-shopping-bag"></i></div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-warning shadow-sm">
            <div class="inner text-white"><h3>...</h3><p>Total Item</p></div>
            <div class="icon"><i class="fas fa-box-open"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>Tren Penjualan Harian</h3>
            </div>
            <div class="card-body">
                <canvas id="salesTrendChart" style="min-height: 320px; height: 320px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-success shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-star mr-2"></i>Produk Terlaris</h3>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" style="min-height: 320px; height: 320px;"></canvas>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
let salesTrendChart = null;
let topProductsChart = null;

function formatRupiah(number) {
    return 'Rp ' + (parseFloat(number || 0)).toLocaleString('id-ID');
}

function getReportFilters() {
    return {
        period: $('#filter-period').val(),
        start_date: $('#filter-start-date').val(),
        end_date: $('#filter-end-date').val(),
        cashier_id: $('#filter-cashier').val()
    };
}

function getQueryString() {
    return $.param(getReportFilters());
}

function toggleReportDateInputs() {
    const isCustom = $('#filter-period').val() === 'custom';
    $('#filter-start-wrapper').toggle(isCustom);
    $('#filter-end-wrapper').toggle(isCustom);
}

function renderSummaryCards(data) {
    $('#report-summary-cards').html(`
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-info shadow-sm">
                <div class="inner"><h3>${formatRupiah(data.omzet)}</h3><p>Omzet</p></div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-success shadow-sm">
                <div class="inner"><h3>${parseInt(data.total_transaksi || 0).toLocaleString('id-ID')}</h3><p>Total Transaksi</p></div>
                <div class="icon"><i class="fas fa-shopping-bag"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner text-white"><h3>${parseInt(data.total_item || 0).toLocaleString('id-ID')}</h3><p>Total Item</p></div>
                <div class="icon"><i class="fas fa-box-open"></i></div>
            </div>
        </div>
    `);
}

function renderDailyChart(rows) {
    const ctx = document.getElementById('salesTrendChart').getContext('2d');

    if (salesTrendChart) salesTrendChart.destroy();

    salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: rows.map(item => item.label),
            datasets: [{
                label: 'Omzet',
                data: rows.map(item => item.total),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.12)',
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function renderTopProductsChart(rows) {
    const ctx = document.getElementById('topProductsChart').getContext('2d');

    if (topProductsChart) topProductsChart.destroy();

    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rows.map(item => item.nama_produk),
            datasets: [{
                label: 'Qty Terjual',
                data: rows.map(item => item.qty),
                backgroundColor: '#28a745',
                borderRadius: 6
            }]
        },
        options: {
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
}

function loadReportSummary() {
    $.get("<?= base_url('laporan-penjualan/summary') ?>", getReportFilters(), function(response) {
        renderSummaryCards(response);
    }, 'json');
}

function loadReportChartData() {
    $.get("<?= base_url('laporan-penjualan/chart') ?>", getReportFilters(), function(response) {
        renderDailyChart(response.daily_series || []);
        renderTopProductsChart(response.top_products || []);
    }, 'json');
}

function applyReportFilter() {
    loadReportSummary();
    loadReportChartData();
}

$(document).ready(function() {
    toggleReportDateInputs();
    applyReportFilter();

    $('#filter-period').on('change', function() {
        toggleReportDateInputs();
    });

    $('#btn-apply-report').on('click', function() {
        applyReportFilter();
    });

    $('#btn-reset-report').on('click', function() {
        $('#filter-period').val('<?= esc($defaultPeriod) ?>').trigger('change');
        $('#filter-start-date').val('<?= esc($defaultStart) ?>');
        $('#filter-end-date').val('<?= esc($defaultEnd) ?>');
        $('#filter-cashier').val('').trigger('change');
        applyReportFilter();
    });

    $('#btn-export-report').on('click', function() {
        window.open("<?= base_url('laporan-penjualan/export-csv') ?>?" + getQueryString(), '_blank');
    });

    $('#btn-print-report').on('click', function() {
        window.open("<?= base_url('laporan-penjualan/print') ?>?" + getQueryString(), '_blank');
    });
});
</script>
<?= $this->endSection() ?>