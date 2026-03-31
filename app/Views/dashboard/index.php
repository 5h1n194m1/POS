<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">Dashboard Analitik</h1>
            </div>
            <div class="col-sm-6 text-sm-right">
                <span class="badge badge-info p-2">Hari Ini: <?= date('d F Y') ?></span>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow">
                    <div class="inner">
                        <h3>Rp <?= number_format($revenue_today ?? 0, 0, ',', '.') ?></h3>
                        <p>Pendapatan Hari Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                    <a href="#" class="small-box-footer">Detail Transaksi <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow">
                    <div class="inner">
                        <h3><?= $total_orders_today ?? 0 ?></h3>
                        <p>Transaksi Hari Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <a href="#" class="small-box-footer">Lihat Penjualan <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning shadow">
                    <div class="inner text-white">
                        <h3><?= $total_products ?? 0 ?></h3>
                        <p>Total Produk</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <a href="<?= base_url('product') ?>" class="small-box-footer" style="color: rgba(255,255,255,0.8) !important;">Kelola Produk <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow">
                    <div class="inner">
                        <h3 class="nav-low-stock-text"><?= $low_stock_count ?? 0 ?></h3>
                        <p>Stok Kritis (<= 5)</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="#stok-rendah" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Tren 7 Hari Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card card-success card-outline shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Performa Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="stok-rendah">
            <div class="col-12">
                <div class="card card-danger card-outline shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title text-bold text-danger">Peringatan Stok Rendah</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th class="pl-4">Nama Produk</th>
                                    <th>Kode</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="text-right pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($low_stock_items)): ?>
                                    <?php foreach($low_stock_items as $item): ?>
                                    <tr>
                                        <td class="pl-4 font-weight-bold"><?= esc($item['nama_produk']) ?></td>
                                        <td><span class="badge badge-secondary"><?= esc($item['kode_produk']) ?></span></td>
                                        <td class="text-center">
                                            <span class="badge badge-danger"><?= $item['stok'] ?> Unit</span>
                                        </td>
                                        <td class="text-right pr-4">
                                            <a href="<?= base_url('product?edit_id=' . $item['id']) ?>" class="btn btn-xs btn-primary shadow-sm">
                                                <i class="fas fa-edit"></i> Update
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Semua stok aman terjaga.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // 1. AJAX UNTUK NAVBAR (Agar loading navbar cepat & asinkron)
    function loadNavbarData() {
        $.ajax({
            url: "<?= base_url('dashboard/getNavbarData') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                // Update elemen di navbar (pastikan di layout/main.php elemen ini punya ID berikut)
                $('#nav-user-name').text(response.nama_user);
                if(response.low_stock_count > 0) {
                    $('#nav-badge-stock').text(response.low_stock_count).show();
                    $('.nav-low-stock-text').text(response.low_stock_count);
                } else {
                    $('#nav-badge-stock').hide();
                }
            }
        });
    }

    $(document).ready(function() {
        loadNavbarData(); // Panggil saat halaman siap

        // 2. LOGIK GRAFIK
        const dailyData = <?= json_encode($chart_data) ?>;
        const monthlyData = <?= json_encode($chart_monthly) ?>;

        Chart.defaults.color = '#666';
        Chart.defaults.font.family = "'Source Sans Pro', sans-serif";

        // Grafik Harian
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyData.map(item => item.tgl),
                datasets: [{
                    label: 'Omzet',
                    data: dailyData.map(item => item.total),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Grafik Bulanan
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(item => item.bulan),
                datasets: [{
                    label: 'Total Penjualan',
                    data: monthlyData.map(item => item.total),
                    backgroundColor: '#28a745',
                    borderRadius: 4
                }]
            },
            options: { maintainAspectRatio: false }
        });
    });
</script>
<?= $this->endSection() ?>