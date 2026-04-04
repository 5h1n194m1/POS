<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    @media (max-width: 991.98px) {
        .profit-action-stack .btn {
            width: 100%;
            margin-right: 0 !important;
            margin-bottom: 10px;
        }

        #profit-summary-cards .small-box .inner h3 {
            font-size: 1.2rem;
            white-space: normal;
        }

        #profitChart {
            min-height: 280px !important;
            height: 280px !important;
        }
    }
</style>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Laporan Keuntungan</h1>
                <small class="text-muted">Pantau laba bersih, diskon member, dan biaya modal secara rapi.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="<?= base_url('laporan-penjualan') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line mr-1"></i> Buka Laporan Penjualan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter Keuntungan</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Periode</label>
                <select id="profit-period" class="form-control">
                    <option value="today" <?= $defaultPeriod === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                    <option value="last_7_days" <?= $defaultPeriod === 'last_7_days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="this_month" <?= $defaultPeriod === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="this_year" <?= $defaultPeriod === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                    <option value="custom" <?= $defaultPeriod === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                </select>
            </div>
            <div class="col-md-2 mb-3" id="profit-start-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Mulai</label>
                <input type="date" id="profit-start-date" class="form-control" value="<?= esc($defaultStart) ?>">
            </div>
            <div class="col-md-2 mb-3" id="profit-end-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Selesai</label>
                <input type="date" id="profit-end-date" class="form-control" value="<?= esc($defaultEnd) ?>">
            </div>
            <div class="col-md-2 mb-3">
                <label class="small font-weight-bold text-muted">Kasir</label>
                <select id="profit-cashier" class="form-control select2">
                    <option value="">Semua Kasir</option>
                    <?php foreach ($cashiers as $cashier): ?>
                        <option value="<?= $cashier['id'] ?>"><?= esc($cashier['fullname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Cari Invoice / Member / Kasir</label>
                <input type="text" id="profit-keyword" class="form-control" placeholder="Cari invoice / member / kasir...">
            </div>
        </div>

        <div class="d-flex justify-content-between flex-wrap profit-action-stack">
            <div class="mb-2 w-100 w-md-auto">
                <button type="button" id="btn-export-profit" class="btn btn-success mr-2">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </button>
                <button type="button" id="btn-print-profit" class="btn btn-dark">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>

            <div class="mb-2 w-100 w-md-auto text-md-right">
                <button type="button" id="btn-reset-profit" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
                <button type="button" id="btn-apply-profit" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row" id="profit-summary-cards">
    <div class="col-lg-3 col-md-6 col-12"><div class="small-box bg-success shadow-sm"><div class="inner"><h3>...</h3><p>Laba Bersih</p></div><div class="icon"><i class="fas fa-coins"></i></div></div></div>
    <div class="col-lg-3 col-md-6 col-12"><div class="small-box bg-info shadow-sm"><div class="inner"><h3>...</h3><p>Omzet Kotor</p></div><div class="icon"><i class="fas fa-wallet"></i></div></div></div>
    <div class="col-lg-3 col-md-6 col-12"><div class="small-box bg-warning shadow-sm"><div class="inner text-white"><h3>...</h3><p>Total Diskon</p></div><div class="icon"><i class="fas fa-percent"></i></div></div></div>
    <div class="col-lg-3 col-md-6 col-12"><div class="small-box bg-danger shadow-sm"><div class="inner"><h3>...</h3><p>Total Modal</p></div><div class="icon"><i class="fas fa-dolly-flatbed"></i></div></div></div>
</div>

<div class="card card-outline card-success shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-chart-area mr-2"></i>Tren Omzet vs Laba</h3>
    </div>
    <div class="card-body">
        <canvas id="profitChart" style="min-height: 320px; height: 320px;"></canvas>
    </div>
</div>

<div class="card card-outline card-secondary shadow-sm mt-4">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Detail Keuntungan Transaksi</h3>
    </div>
    <div class="card-body p-0" id="profit-table-wrapper">
        <div class="text-center py-5 text-muted">Memuat data keuntungan...</div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
let profitChart = null;
let keywordTimer = null;

function formatRupiah(number) {
    return 'Rp ' + (parseFloat(number || 0)).toLocaleString('id-ID');
}

function getProfitFilters() {
    return {
        period: $('#profit-period').val(),
        start_date: $('#profit-start-date').val(),
        end_date: $('#profit-end-date').val(),
        cashier_id: $('#profit-cashier').val(),
        keyword: $('#profit-keyword').val()
    };
}

function getProfitQueryString() {
    return $.param(getProfitFilters());
}

function toggleProfitDateInputs() {
    const isCustom = $('#profit-period').val() === 'custom';
    $('#profit-start-wrapper').toggle(isCustom);
    $('#profit-end-wrapper').toggle(isCustom);
}

function renderProfitSummary(data) {
    $('#profit-summary-cards').html(`
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-success shadow-sm"><div class="inner"><h3>${formatRupiah(data.laba_bersih)}</h3><p>Laba Bersih</p></div><div class="icon"><i class="fas fa-coins"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-info shadow-sm"><div class="inner"><h3>${formatRupiah(data.omzet_kotor)}</h3><p>Omzet Kotor</p></div><div class="icon"><i class="fas fa-wallet"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-warning shadow-sm"><div class="inner text-white"><h3>${formatRupiah(data.total_diskon)}</h3><p>Total Diskon</p></div><div class="icon"><i class="fas fa-percent"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-danger shadow-sm"><div class="inner"><h3>${formatRupiah(data.total_modal)}</h3><p>Total Modal</p></div><div class="icon"><i class="fas fa-dolly-flatbed"></i></div></div>
        </div>
    `);
}

function renderProfitChart(rows) {
    const ctx = document.getElementById('profitChart').getContext('2d');

    if (profitChart) profitChart.destroy();

    profitChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: rows.map(item => item.label),
            datasets: [
                {
                    label: 'Omzet Kotor',
                    data: rows.map(item => item.omzet_kotor),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.10)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 3
                },
                {
                    label: 'Laba Bersih',
                    data: rows.map(item => item.laba_bersih),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.08)',
                    fill: false,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function loadProfitSummary() {
    $.get("<?= base_url('laporan-keuntungan/summary') ?>", getProfitFilters(), function(response) {
        renderProfitSummary(response);
    }, 'json');
}

function loadProfitChart() {
    $.get("<?= base_url('laporan-keuntungan/chart') ?>", getProfitFilters(), function(response) {
        renderProfitChart(response.daily_series || []);
    }, 'json');
}

function loadProfitTable() {
    $('#profit-table-wrapper').html('<div class="text-center py-5 text-muted">Memuat data keuntungan...</div>');
    $.get("<?= base_url('laporan-keuntungan/data') ?>", getProfitFilters(), function(html) {
        $('#profit-table-wrapper').html(html);
    });
}

function applyProfitFilter() {
    loadProfitSummary();
    loadProfitChart();
    loadProfitTable();
}

$(document).ready(function() {
    toggleProfitDateInputs();
    applyProfitFilter();

    $('#profit-period').on('change', toggleProfitDateInputs);

    $('#btn-apply-profit').on('click', applyProfitFilter);

    $('#btn-reset-profit').on('click', function() {
        $('#profit-period').val('<?= esc($defaultPeriod) ?>').trigger('change');
        $('#profit-start-date').val('<?= esc($defaultStart) ?>');
        $('#profit-end-date').val('<?= esc($defaultEnd) ?>');
        $('#profit-cashier').val('').trigger('change');
        $('#profit-keyword').val('');
        applyProfitFilter();
    });

    $('#profit-keyword').on('keyup', function() {
        clearTimeout(keywordTimer);
        keywordTimer = setTimeout(function() {
            applyProfitFilter();
        }, 400);
    });

    $('#btn-export-profit').on('click', function() {
        window.open("<?= base_url('laporan-keuntungan/export-csv') ?>?" + getProfitQueryString(), '_blank');
    });

    $('#btn-print-profit').on('click', function() {
        window.open("<?= base_url('laporan-keuntungan/print') ?>?" + getProfitQueryString(), '_blank');
    });
});
</script>
<?= $this->endSection() ?>
