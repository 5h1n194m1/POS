<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Manajemen Stok Barang</h3>
        <p class="text-muted small">Kelola inventaris produk POS Anda dengan mudah secara realtime.</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus mr-2"></i>Tambah Barang
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="product-table">
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
                <tbody id="product-list">
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="spinner-border text-primary spinner-border-sm mr-2"></div>
                            Memuat data produk...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-add" autocomplete="off">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title font-weight-bold">Tambah Produk Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label class="small font-weight-bold">Kode Produk</label>
                                <input type="text" name="kode_produk" id="add-kode" class="form-control form-control-sm" placeholder="Contoh: BRG001" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Kategori</label>
                                <input type="text" name="kategori" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Harga Beli</label>
                                <input type="number" name="harga_beli" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Stok Awal</label>
                                <input type="number" name="stok" class="form-control form-control-sm" required min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" id="btn-save">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-edit" autocomplete="off">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header bg-info text-white border-0">
                    <h5 class="modal-title font-weight-bold">Edit Produk</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label class="small font-weight-bold">Kode Produk</label>
                                <input type="text" name="kode_produk" id="edit-kode" class="form-control form-control-sm" readonly bg-light>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Nama Produk</label>
                                <input type="text" name="nama_produk" id="edit-nama" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Kategori</label>
                                <input type="text" name="kategori" id="edit-kategori" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Harga Beli</label>
                                <input type="number" name="harga_beli" id="edit-beli" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Harga Jual</label>
                                <input type="number" name="harga_jual" id="edit-jual" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Stok</label>
                                <input type="number" name="stok" id="edit-stok" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info btn-sm px-4" id="btn-update">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Load data pertama kali
        loadTable();

        // 1. FUNGSI LOAD DATA (READ)
        function loadTable() {
            $.ajax({
                url: "<?= base_url('product/listData') ?>",
                type: "GET",
                dataType: "JSON",
                success: function(res) {
                    let html = '';
                    if (res.data.length > 0) {
                        res.data.forEach(function(p) {
                            let badgeStok = `<span class="badge badge-success">${p.stok}</span>`;
                            if (p.stok <= 0) badgeStok = `<span class="badge badge-dark">Habis</span>`;
                            else if (p.stok < 5) badgeStok = `<span class="badge badge-danger">Kritis: ${p.stok}</span>`;

                            html += `
                                <tr>
                                    <td class="pl-4">
                                        <span class="badge badge-light text-dark border font-monospace">${p.kode_produk}</span>
                                    </td>
                                    <td class="font-weight-bold text-dark">${p.nama_produk}</td>
                                    <td><span class="text-muted small">${p.kategori || '-'}</span></td>
                                    <td class="text-primary font-weight-bold">Rp ${parseInt(p.harga_jual).toLocaleString('id-ID')}</td>
                                    <td class="text-center">${badgeStok}</td>
                                    <td class="text-center pr-4">
                                        <div class="btn-group shadow-sm">
                                            <button class="btn btn-xs btn-white border btn-edit" 
                                                data-id="${p.id}" data-kode="${p.kode_produk}" 
                                                data-nama="${p.nama_produk}" data-kategori="${p.kategori}" 
                                                data-beli="${p.harga_beli}" data-jual="${p.harga_jual}" 
                                                data-stok="${p.stok}"><i class="fas fa-edit text-info"></i></button>
                                            <button class="btn btn-xs btn-white border btn-delete" data-id="${p.id}"><i class="fas fa-trash text-danger"></i></button>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        html = `<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data.</td></tr>`;
                    }
                    $('#product-list').html(html);

                    // --- FITUR AUTO-EDIT VIA URL DIMASUKKAN DI SINI ---
                    checkUrlForEdit();
                },
                error: function(xhr, status, error) {
                    // Jika server error, tampilkan pesan di dalam tabel agar user tahu
                    console.error(xhr.responseText); // Cek detailnya di F12 Console
                    $('#product-list').html(`
                        <tr>
                            <td colspan="6" class="text-center py-5 text-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Gagal memuat data. Silakan cek koneksi atau database.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Fungsi pengecekan parameter URL
        function checkUrlForEdit() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit_id');

            if (editId) {
                // Cari tombol yang barusan di-render oleh AJAX
                const targetBtn = $(`.btn-edit[data-id="${editId}"]`);
                if (targetBtn.length) {
                    targetBtn.trigger('click');
                    // Bersihkan URL tanpa reload agar rapi
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            }
        }

        // 2. FUNGSI UPDATE CSRF TOKEN
        function updateCSRF(token) {
            $('input[name=<?= csrf_token() ?>]').val(token);
        }

        // 3. PROSES SIMPAN (CREATE)
        $('#form-add').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#btn-save');
            $.ajax({
                url: "<?= base_url('product/save') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() { btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true); },
                success: function(res) {
                    updateCSRF(res.token);
                    if (res.status === 'success') {
                        $('#addModal').modal('hide');
                        $('#form-add')[0].reset();
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 1500, showConfirmButton: false });
                        loadTable();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: Object.values(res.errors)[0] });
                    }
                },
                complete: function() { btn.html('Simpan Produk').prop('disabled', false); }
            });
        });

        // 4. PROSES UPDATE
        $('#form-edit').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#btn-update');
            $.ajax({
                url: "<?= base_url('product/update') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() { btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true); },
                success: function(res) {
                    updateCSRF(res.token);
                    if (res.status === 'success') {
                        $('#editModal').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Diperbarui', text: res.message, timer: 1500, showConfirmButton: false });
                        loadTable();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: Object.values(res.errors)[0] });
                    }
                },
                complete: function() { btn.html('Update Data').prop('disabled', false); }
            });
        });

        // 5. PROSES HAPUS (DELETE)
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Aksi ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('product/delete') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(res) {
                            updateCSRF(res.token);
                            Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message, timer: 1500, showConfirmButton: false });
                            loadTable();
                        }
                    });
                }
            });
        });

        // Event: Tombol Edit di Click (Event Delegation)
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

        $('#addModal').on('shown.bs.modal', function () { $('#add-kode').focus(); });
    });
</script>
<?= $this->endSection() ?>