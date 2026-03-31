<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
            <div class="btn-group">
                <a href="<?= base_url('laporan?filter=semua') ?>" class="btn btn-sm btn-outline-primary <?= $filter == 'semua' ? 'active' : '' ?>">Semua</a>
                <a href="<?= base_url('laporan?filter=hari_ini') ?>" class="btn btn-sm btn-outline-primary <?= $filter == 'hari_ini' ? 'active' : '' ?>">Hari Ini</a>
                <a href="<?= base_url('laporan?filter=bulan_ini') ?>" class="btn btn-sm btn-outline-primary <?= $filter == 'bulan_ini' ? 'active' : '' ?>">Bulan Ini</a>
                <a href="<?= base_url('laporan?filter=tahun_ini') ?>" class="btn btn-sm btn-outline-primary <?= $filter == 'tahun_ini' ? 'active' : '' ?>">Tahun Ini</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total Harga</th>
                            <th>Bayar</th>
                            <th>Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($penjualan as $row) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                <td><?= $row['fullname'] ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['bayar'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="<?= base_url('kasir/print_nota/' . $row['id']) ?>" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-print"></i> Re-Print
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>