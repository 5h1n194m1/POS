<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Data Produk</h1>
                <small class="text-muted">Kelola stok, harga beli, dan harga jual produk.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <button class="btn btn-primary" id="btn-add-product">
                    <i class="fas fa-plus mr-1"></i> Tambah Produk
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('msg')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="product-search" class="form-control" placeholder="Cari nama / kode / kategori...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga Beli</th>
                        <th class="text-right">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th>Updated</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Memuat data produk...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="productForm" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="product-id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span style="color:#fff;">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Produk</label>
                    <input type="text" name="kode_produk" id="product-kode" class="form-control">
                </div>

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" id="product-nama" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" id="product-kategori" class="form-control">
                </div>

                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" step="0.01" name="harga_beli" id="product-harga-beli" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" step="0.01" name="harga_jual" id="product-harga-jual" class="form-control" required>
                </div>

                <div class="form-group mb-0">
                    <label>Stok</label>
                    <input type="number" name="stok" id="product-stok" class="form-control" required min="0">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn-save-product">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
let allProducts = [];
let productMode = 'add';

function escapeHtml(text) {
    return $('<div>').text(text ?? '').html();
}

function formatRupiah(number) {
    return 'Rp ' + (parseFloat(number || 0)).toLocaleString('id-ID');
}

function renderProducts(rows) {
    if (!rows.length) {
        $('#product-table-body').html(`
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">Tidak ada data produk.</td>
            </tr>
        `);
        return;
    }

    let html = '';
    rows.forEach(product => {
        html += `
            <tr>
                <td>${parseInt(product.id)}</td>
                <td>${escapeHtml(product.kode_produk || '-')}</td>
                <td class="font-weight-bold">${escapeHtml(product.nama_produk || '-')}</td>
                <td>${escapeHtml(product.kategori || '-')}</td>
                <td class="text-right">${formatRupiah(product.harga_beli)}</td>
                <td class="text-right font-weight-bold text-primary">${formatRupiah(product.harga_jual)}</td>
                <td class="text-center"><span class="badge badge-info">${parseInt(product.stok || 0)}</span></td>
                <td>${product.updated_at || '-'}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-product" data-id="${product.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <a href="<?= base_url('product/delete') ?>/${product.id}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `;
    });

    $('#product-table-body').html(html);
}

function loadProducts() {
    $.get("<?= base_url('product/listData') ?>", function(response) {
        allProducts = response.data || [];
        renderProducts(allProducts);
    }, 'json');
}

function resetProductForm() {
    $('#productForm')[0].reset();
    $('#product-id').val('');
}

function openAddProductModal() {
    productMode = 'add';
    resetProductForm();
    $('#productModalTitle').text('Tambah Produk');
    $('#productModal').modal('show');
}

function openEditProductModal(id) {
    const product = allProducts.find(item => parseInt(item.id) === parseInt(id));
    if (!product) return;

    productMode = 'edit';
    resetProductForm();

    $('#productModalTitle').text('Edit Produk');
    $('#product-id').val(product.id);
    $('#product-kode').val(product.kode_produk);
    $('#product-nama').val(product.nama_produk);
    $('#product-kategori').val(product.kategori);
    $('#product-harga-beli').val(product.harga_beli);
    $('#product-harga-jual').val(product.harga_jual);
    $('#product-stok').val(product.stok);

    $('#productModal').modal('show');
}

$(document).ready(function() {
    loadProducts();

    $('#btn-add-product').on('click', function() {
        openAddProductModal();
    });

    $('#product-search').on('keyup', function() {
        const keyword = $(this).val().toLowerCase();

        const filtered = allProducts.filter(product => {
            return (product.nama_produk || '').toLowerCase().includes(keyword)
                || (product.kode_produk || '').toLowerCase().includes(keyword)
                || (product.kategori || '').toLowerCase().includes(keyword);
        });

        renderProducts(filtered);
    });

    $(document).on('click', '.btn-edit-product', function() {
        openEditProductModal($(this).data('id'));
    });

    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        const url = productMode === 'add'
            ? "<?= base_url('product/save') ?>"
            : "<?= base_url('product/update') ?>";

        const $btn = $('#btn-save-product');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('#productModal').modal('hide');
                Swal.fire('Berhasil', response.msg, 'success');
                loadProducts();
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    msg = xhr.responseJSON.msg;
                }
                Swal.fire('Gagal', msg, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>