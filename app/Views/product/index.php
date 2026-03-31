<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Manajemen Stok Barang</h3>
        <p class="text-muted small">Kelola inventaris produk POS Anda dengan mudah.</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus mr-2"></i>Tambah Barang
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="pl-4" width="15%">Kode</th>
                        <th width="25%">Nama Produk</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Harga Jual</th>
                        <th class="text-center" width="10%">Stok</th>
                        <th class="text-center pr-4" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="pl-4">
                                    <span class="badge badge-light text-dark border font-monospace"><?= esc($p['kode_produk']) ?></span>
                                </td>
                                <td class="font-weight-bold text-dark"><?= esc($p['nama_produk']) ?></td>
                                <td><span class="text-muted small"><?= esc($p['kategori'] ?: '-') ?></span></td>
                                <td class="text-primary font-weight-bold">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if ($p['stok'] <= 0): ?>
                                        <span class="badge badge-dark">Habis</span>
                                    <?php elseif ($p['stok'] < 5): ?>
                                        <span class="badge badge-danger">Kritis: <?= $p['stok'] ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $p['stok'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pr-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit" 
                                                data-id="<?= $p['id'] ?>"
                                                data-kode="<?= $p['kode_produk'] ?>"
                                                data-nama="<?= $p['nama_produk'] ?>"
                                                data-kategori="<?= $p['kategori'] ?>"
                                                data-beli="<?= $p['harga_beli'] ?>"
                                                data-jual="<?= $p['harga_jual'] ?>"
                                                data-stok="<?= $p['stok'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= base_url('product/delete/' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?= base_url('product/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Tambah Produk Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Produk</label>
                                <input type="text" name="kode_produk" id="add-kode" class="form-control" placeholder="Contoh: BRG001" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" name="kategori" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Beli</label>
                                <input type="number" name="harga_beli" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Stok Awal</label>
                                <input type="number" name="stok" class="form-control" required min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?= base_url('product/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold">Edit Produk</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Produk</label>
                                <input type="text" name="kode_produk" id="edit-kode" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama_produk" id="edit-nama" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" name="kategori" id="edit-kategori" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Beli</label>
                                <input type="number" name="harga_beli" id="edit-beli" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Harga Jual</label>
                                <input type="number" name="harga_jual" id="edit-jual" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" name="stok" id="edit-stok" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Event delegation untuk Edit
        $(document).on('click', '.btn-edit', function() {
            const data = $(this).data();
            $('#edit-id').val(data.id);
            $('#edit-kode').val(data.kode);
            $('#edit-nama').val(data.nama);
            $('#edit-kategori').val(data.kategori);
            $('#edit-beli').val(data.beli);
            $('#edit-jual').val(data.jual);
            $('#edit-stok').val(data.stok);
            $('#editModal').modal('show');
        });

        // Fokus Kode saat tambah modal dibuka
        $('#addModal').on('shown.bs.modal', function () {
            $('#add-kode').focus();
        });

        // SweetAlert Konfirmasi Hapus
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // Alert sukses dari controller
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', timer: 2000, showConfirmButton: false });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= session()->getFlashdata('error') ?>' });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>