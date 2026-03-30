<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Manajemen Stok Barang</h3>
        <p class="text-muted">Kelola inventaris toko Anda di sini.</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Barang
    </button>
</div>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td class="ps-4"><span class="badge bg-light text-dark border"><?= $p['kode_produk'] ?></span></td>
                            <td class="fw-semibold"><?= $p['nama_produk'] ?></td>
                            <td><span class="text-muted small"><?= $p['kategori'] ?></span></td>
                            <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
                            <td class="text-primary fw-bold">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge <?= ($p['stok'] < 5) ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $p['stok'] ?>
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="<?= base_url('product/delete/'.$p['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Hapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                Belum ada data barang.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('product/save') ?>" method="post">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Input Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" placeholder="BRG-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Minuman">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Beli (Rp)</label>
                            <input type="number" name="harga_beli" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Jual (Rp)</label>
                            <input type="number" name="harga_jual" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>