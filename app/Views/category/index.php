<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header px-1">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold">Kategori Produk</h1>
            <small class="text-muted">Kelola kategori agar input barang lebih rapi dan terstruktur.</small>
        </div>
        <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
            <button class="btn btn-primary" id="btn-add-category">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </button>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="category-search" class="form-control" placeholder="Cari nama kategori...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th width="80">ID</th>
                        <th>Nama Kategori</th>
                        <th width="140" class="text-center">Total Produk</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="category-table-body">
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Memuat data kategori...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-md-none p-3" id="category-card-list">
            <div class="text-center text-muted py-4">Memuat data kategori...</div>
        </div>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="categoryForm" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="category-id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span style="color:#fff;">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-0">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="category-name" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn-save-category">
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
let allCategories = [];
let categoryMode = 'add';

function escapeHtml(text) {
    return $('<div>').text(text ?? '').html();
}

function renderCategories(rows) {
    if (!rows.length) {
        $('#category-table-body').html(`
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data kategori.</td>
            </tr>
        `);

        $('#category-card-list').html(`
            <div class="text-center py-4 text-muted">Tidak ada data kategori.</div>
        `);
        return;
    }

    let tableHtml = '';
    let cardHtml = '';

    rows.forEach(category => {
        tableHtml += `
            <tr>
                <td>${parseInt(category.id)}</td>
                <td class="font-weight-bold">${escapeHtml(category.nama_kategori)}</td>
                <td class="text-center">
                    <span class="badge badge-info px-3 py-2">${parseInt(category.total_produk || 0)}</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-category" data-id="${category.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-category" data-id="${category.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        cardHtml += `
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="font-weight-bold">${escapeHtml(category.nama_kategori)}</div>
                            <small class="text-muted">ID: ${parseInt(category.id)}</small>
                        </div>
                        <span class="badge badge-info px-3 py-2">${parseInt(category.total_produk || 0)} produk</span>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-category" data-id="${category.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-category" data-id="${category.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    $('#category-table-body').html(tableHtml);
    $('#category-card-list').html(cardHtml);
}

function loadCategories() {
    $.get("<?= base_url('kategori/listData') ?>", function(response) {
        allCategories = response.data || [];
        renderCategories(allCategories);
    }, 'json');
}

function resetCategoryForm() {
    $('#categoryForm')[0].reset();
    $('#category-id').val('');
}

function openAddCategoryModal() {
    categoryMode = 'add';
    resetCategoryForm();
    $('#categoryModalTitle').text('Tambah Kategori');
    $('#categoryModal').modal('show');
}

function openEditCategoryModal(id) {
    const category = allCategories.find(item => parseInt(item.id) === parseInt(id));
    if (!category) return;

    categoryMode = 'edit';
    resetCategoryForm();

    $('#categoryModalTitle').text('Edit Kategori');
    $('#category-id').val(category.id);
    $('#category-name').val(category.nama_kategori);

    $('#categoryModal').modal('show');
}

$(document).ready(function() {
    loadCategories();

    $('#btn-add-category').on('click', function() {
        openAddCategoryModal();
    });

    $('#category-search').on('keyup', function() {
        const keyword = $(this).val().toLowerCase();
        const filtered = allCategories.filter(item =>
            (item.nama_kategori || '').toLowerCase().includes(keyword)
        );
        renderCategories(filtered);
    });

    $(document).on('click', '.btn-edit-category', function() {
        openEditCategoryModal($(this).data('id'));
    });

    $(document).on('click', '.btn-delete-category', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus kategori?',
            text: 'Kategori yang masih dipakai produk tidak bisa dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "<?= base_url('kategori/delete') ?>/" + id,
                type: "POST",
                data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: "json",
                success: function(response) {
                    Swal.fire('Berhasil', response.msg, 'success');
                    loadCategories();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.msg
                        ? xhr.responseJSON.msg
                        : 'Gagal menghapus kategori.';
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        });
    });

    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();

        const url = categoryMode === 'add'
            ? "<?= base_url('kategori/save') ?>"
            : "<?= base_url('kategori/update') ?>";

        const $btn = $('#btn-save-category');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('#categoryModal').modal('hide');
                Swal.fire('Berhasil', response.msg, 'success');
                loadCategories();
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