<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Manajemen Stok Barang</h3>
        <p class="text-muted small">Kelola inventaris produk POS Anda dengan mudah.</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Barang
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="15%">Kode</th>
                        <th width="25%">Nama Produk</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Harga Beli</th>
                        <th width="15%">Harga Jual</th>
                        <th class="text-center" width="10%">Stok</th>
                        <th class="text-center pe-4" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace"><?= esc($p['kode_produk']) ?></span>
                                </td>
                                <td class="fw-semibold text-dark"><?= esc($p['nama_produk']) ?></td>
                                <td><span class="text-muted small"><?= esc($p['kategori'] ?: '-') ?></span></td>
                                <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
                                <td class="text-primary fw-bold">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if ($p['stok'] <= 0): ?>
                                        <span class="badge bg-dark">Habis</span>
                                    <?php elseif ($p['stok'] < 5): ?>
                                        <span class="badge bg-danger">Kritis: <?= $p['stok'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $p['stok'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <a href="<?= base_url('product/delete/' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Hapus produk ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box2 fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= base_url('product/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Barang Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kode Produk</label>
                            <input type="text" name="kode_produk" 
                                   class="form-control <?= session('errors.kode_produk') ? 'is-invalid' : '' ?>" 
                                   placeholder="Contoh: BRG001" value="<?= old('kode_produk') ?>" required>
                            <div class="invalid-feedback"><?= session('errors.kode_produk') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Elektronik/Atk" value="<?= old('kategori') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" 
                                   class="form-control <?= session('errors.nama_produk') ? 'is-invalid' : '' ?>" 
                                   placeholder="Masukkan nama barang lengkap" value="<?= old('nama_produk') ?>" required>
                            <div class="invalid-feedback"><?= session('errors.nama_produk') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Beli</label>
                            <div class="input-group">
                                <span class="input-group-text small">Rp</span>
                                <input type="number" name="harga_beli" class="form-control" value="<?= old('harga_beli') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text small">Rp</span>
                                <input type="number" name="harga_jual" class="form-control" value="<?= old('harga_jual') ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="<?= old('stok', 0) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-toggle="modal" data-bs-target="#addModal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Inventaris</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('addModal');
        const productModal = new bootstrap.Modal(modalEl);

        // Optimalisasi Pop-up Error & Auto-Open Modal
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                productModal.show();
            });
            // Tampilkan modal langsung agar error input terlihat
            productModal.show();
        <?php endif; ?>

        // Optimalisasi Pop-up Sukses
        <?php if (session()->getFlashdata('success')) : ?>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('success') ?>'
            });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>