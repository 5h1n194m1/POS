<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Riwayat Transaksi</h1>
                <small class="text-muted">Lihat histori transaksi, detail item, dan cetak ulang nota.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="<?= base_url('laporan-penjualan') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line mr-1"></i> Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row" id="riwayat-summary-cards">
    <div class="col-lg-3 col-md-6 col-12">
        <div class="small-box bg-info shadow-sm"><div class="inner"><h3>...</h3><p>Omzet</p></div><div class="icon"><i class="fas fa-wallet"></i></div></div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="small-box bg-success shadow-sm"><div class="inner"><h3>...</h3><p>Total Transaksi</p></div><div class="icon"><i class="fas fa-shopping-bag"></i></div></div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="small-box bg-warning shadow-sm"><div class="inner text-white"><h3>...</h3><p>Total Item</p></div><div class="icon"><i class="fas fa-box-open"></i></div></div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="small-box bg-danger shadow-sm"><div class="inner"><h3>...</h3><p>Rata-rata Transaksi</p></div><div class="icon"><i class="fas fa-chart-pie"></i></div></div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter Riwayat</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Periode</label>
                <select id="history-period" class="form-control">
                    <option value="today" <?= $defaultPeriod === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                    <option value="last_7_days" <?= $defaultPeriod === 'last_7_days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="this_month" <?= $defaultPeriod === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="this_year" <?= $defaultPeriod === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                    <option value="custom" <?= $defaultPeriod === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                </select>
            </div>

            <div class="col-md-2 mb-3" id="history-start-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Mulai</label>
                <input type="date" id="history-start-date" class="form-control" value="<?= esc($defaultStart) ?>">
            </div>

            <div class="col-md-2 mb-3" id="history-end-wrapper">
                <label class="small font-weight-bold text-muted">Tanggal Selesai</label>
                <input type="date" id="history-end-date" class="form-control" value="<?= esc($defaultEnd) ?>">
            </div>

            <div class="col-md-2 mb-3">
                <label class="small font-weight-bold text-muted">Kasir</label>
                <select id="history-cashier" class="form-control select2">
                    <option value="">Semua Kasir</option>
                    <?php foreach ($cashiers as $cashier): ?>
                        <option value="<?= $cashier['id'] ?>"><?= esc($cashier['fullname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="small font-weight-bold text-muted">Cari Invoice / Kasir</label>
                <input type="text" id="history-keyword" class="form-control" placeholder="Cari invoice / nama kasir...">
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" id="btn-reset-history" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-undo mr-1"></i> Reset
            </button>
            <button type="button" id="btn-apply-history" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> Terapkan Filter
            </button>
        </div>
    </div>
</div>

<div class="card card-outline card-secondary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Daftar Transaksi</h3>
    </div>
    <div class="card-body p-0" id="riwayat-table-wrapper">
        <div class="text-center py-5 text-muted">Memuat data transaksi...</div>
    </div>
</div>

<div class="modal fade" id="modalDetailTransaksi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span style="color:#fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detail-transaksi-content">
                <div class="text-center py-4 text-muted">Memuat detail transaksi...</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
let keywordTimer = null;

function formatRupiah(number) {
    return 'Rp ' + (parseFloat(number || 0)).toLocaleString('id-ID');
}

function escapeHtml(text) {
    return $('<div>').text(text ?? '').html();
}

function getHistoryFilters() {
    return {
        period: $('#history-period').val(),
        start_date: $('#history-start-date').val(),
        end_date: $('#history-end-date').val(),
        cashier_id: $('#history-cashier').val(),
        keyword: $('#history-keyword').val()
    };
}

function toggleHistoryDateInputs() {
    const isCustom = $('#history-period').val() === 'custom';
    $('#history-start-wrapper').toggle(isCustom);
    $('#history-end-wrapper').toggle(isCustom);
}

function renderHistorySummary(data) {
    $('#riwayat-summary-cards').html(`
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-info shadow-sm"><div class="inner"><h3>${formatRupiah(data.omzet)}</h3><p>Omzet</p></div><div class="icon"><i class="fas fa-wallet"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-success shadow-sm"><div class="inner"><h3>${parseInt(data.total_transaksi || 0).toLocaleString('id-ID')}</h3><p>Total Transaksi</p></div><div class="icon"><i class="fas fa-shopping-bag"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-warning shadow-sm"><div class="inner text-white"><h3>${parseInt(data.total_item || 0).toLocaleString('id-ID')}</h3><p>Total Item</p></div><div class="icon"><i class="fas fa-box-open"></i></div></div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-danger shadow-sm"><div class="inner"><h3>${formatRupiah(data.rata_transaksi)}</h3><p>Rata-rata Transaksi</p></div><div class="icon"><i class="fas fa-chart-pie"></i></div></div>
        </div>
    `);
}

function loadHistorySummary() {
    $.get("<?= base_url('laporan-penjualan/summary') ?>", getHistoryFilters(), function(response) {
        renderHistorySummary(response);
    }, 'json');
}

function loadHistoryTable() {
    $('#riwayat-table-wrapper').html('<div class="text-center py-5 text-muted">Memuat data transaksi...</div>');
    $.get("<?= base_url('riwayat-transaksi/data') ?>", getHistoryFilters(), function(html) {
        $('#riwayat-table-wrapper').html(html);
    });
}

function applyHistoryFilter() {
    loadHistorySummary();
    loadHistoryTable();
}

function loadTransactionDetail(id) {
    $('#detail-transaksi-content').html('<div class="text-center py-4 text-muted">Memuat detail transaksi...</div>');
    $('#modalDetailTransaksi').modal('show');

    $.get("<?= base_url('riwayat-transaksi/detail') ?>/" + id, function(response) {
        if (response.status !== 'success') {
            $('#detail-transaksi-content').html('<div class="alert alert-danger">Gagal memuat detail transaksi.</div>');
            return;
        }

        let itemsHtml = '';
        if (response.items.length) {
            response.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td>
                            <div class="font-weight-bold">${escapeHtml(item.nama_produk)}</div>
                            <small class="text-muted">${escapeHtml(item.kode_produk || '-')}</small>
                        </td>
                        <td class="text-right">${formatRupiah(item.harga_jual)}</td>
                        <td class="text-center">${parseInt(item.qty)}</td>
                        <td class="text-right font-weight-bold">${formatRupiah(item.subtotal)}</td>
                    </tr>
                `;
            });
        } else {
            itemsHtml = `<tr><td colspan="4" class="text-center py-3 text-muted">Item transaksi tidak ditemukan.</td></tr>`;
        }

        $('#detail-transaksi-content').html(`
            <div class="row mb-3">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="120">Invoice</th><td>: ${escapeHtml(response.invoice_no)}</td></tr>
                        <tr><th>Tanggal</th><td>: ${escapeHtml(response.header.created_at)}</td></tr>
                        <tr><th>Kasir</th><td>: ${escapeHtml(response.header.fullname)}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="120">Total</th><td>: <span class="font-weight-bold text-primary">${formatRupiah(response.header.total_harga)}</span></td></tr>
                        <tr><th>Bayar</th><td>: ${formatRupiah(response.header.bayar)}</td></tr>
                        <tr><th>Kembalian</th><td>: <span class="font-weight-bold text-success">${formatRupiah(response.header.kembalian)}</span></td></tr>
                    </table>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                </table>
            </div>
            <div class="text-right mt-3">
                <a href="<?= base_url('penjualan/print_nota') ?>/${response.header.id}" target="_blank" class="btn btn-success">
                    <i class="fas fa-print mr-1"></i> Cetak Ulang Nota
                </a>
            </div>
        `);
    }, 'json').fail(function() {
        $('#detail-transaksi-content').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat detail transaksi.</div>');
    });
}

$(document).ready(function() {
    toggleHistoryDateInputs();
    applyHistoryFilter();

    $('#history-period').on('change', function() {
        toggleHistoryDateInputs();
    });

    $('#btn-apply-history').on('click', function() {
        applyHistoryFilter();
    });

    $('#btn-reset-history').on('click', function() {
        $('#history-period').val('<?= esc($defaultPeriod) ?>').trigger('change');
        $('#history-start-date').val('<?= esc($defaultStart) ?>');
        $('#history-end-date').val('<?= esc($defaultEnd) ?>');
        $('#history-cashier').val('').trigger('change');
        $('#history-keyword').val('');
        applyHistoryFilter();
    });

    $('#history-keyword').on('keyup', function() {
        clearTimeout(keywordTimer);
        keywordTimer = setTimeout(function() {
            applyHistoryFilter();
        }, 400);
    });

    $(document).on('click', '.btn-detail-transaksi', function() {
        loadTransactionDetail($(this).data('id'));
    });
});
</script>
<?= $this->endSection() ?>