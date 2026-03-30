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
                        <th width="15%">Harga Beli</th>
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
                                <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
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
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Hapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box open fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= base_url('product/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Barang Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" value="<?= old('kode_produk') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="<?= old('kategori') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= old('nama_produk') ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control" value="<?= old('harga_beli') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" value="<?= old('harga_jual') ?>" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" value="<?= old('stok', 0) ?>" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Inventaris</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= base_url('product/update') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Edit Data Barang</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Kode Produk</label>
                            <input type="text" name="kode_produk" id="edit-kode" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Kategori</label>
                            <input type="text" name="kategori" id="edit-kategori" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Nama Produk</label>
                        <input type="text" name="nama_produk" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Harga Beli</label>
                            <input type="number" name="harga_beli" id="edit-beli" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Harga Jual</label>
                            <input type="number" name="harga_jual" id="edit-jual" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Stok</label>
                        <input type="number" name="stok" id="edit-stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Fungsi Klik Tombol Edit
        $('.btn-edit').on('click', function() {
            // Ambil data dari atribut button
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const kategori = $(this).data('kategori');
            const beli = $(this).data('beli');
            const jual = $(this).data('jual');
            const stok = $(this).data('stok');

            // Masukkan ke input modal edit
            $('#edit-id').val(id);
            $('#edit-kode').val(kode);
            $('#edit-nama').val(nama);
            $('#edit-kategori').val(kategori);
            $('#edit-beli').val(beli);
            $('#edit-jual').val(jual);
            $('#edit-stok').val(stok);

            // Tampilkan Modal Edit
            $('#editModal').modal('show');
        });

        // Pop-up Error & Auto-Open Modal Tambah (jika ada error validasi)
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#007bff'
            }).then(() => {
                $('#addModal').modal('show');
            });
        <?php endif; ?>

        // Pop-up Sukses
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