<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header px-1">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold">Data Member</h1>
            <small class="text-muted">Kelola pelanggan loyal dan siapkan diskon spesial langsung dari kasir.</small>
        </div>
        <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
            <button class="btn btn-primary" id="btn-add-member">
                <i class="fas fa-plus mr-1"></i> Tambah Member
            </button>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="member-search" class="form-control" placeholder="Cari nama / no member / no hp...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th width="90">ID</th>
                        <th>No Member</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="member-table-body">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Memuat data member...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-md-none p-3" id="member-card-list">
            <div class="text-center text-muted py-4">Memuat data member...</div>
        </div>
    </div>
</div>

<div class="modal fade" id="memberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="memberForm" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="member-id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="memberModalTitle">Tambah Member</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span style="color:#fff;">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>No Member</label>
                    <input type="text" name="no_member" id="member-no" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="member-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" id="member-phone" class="form-control">
                </div>
                <div class="form-group mb-0">
                    <label>Alamat</label>
                    <textarea name="alamat" id="member-address" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn-save-member">
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
let allMembers = [];
let memberMode = 'add';

function escapeHtml(text) {
    return $('<div>').text(text ?? '').html();
}

function renderMembers(rows) {
    if (!rows.length) {
        $('#member-table-body').html(`
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data member.</td>
            </tr>
        `);

        $('#member-card-list').html(`
            <div class="text-center py-4 text-muted">Tidak ada data member.</div>
        `);
        return;
    }

    let tableHtml = '';
    let cardHtml = '';

    rows.forEach(member => {
        tableHtml += `
            <tr>
                <td>${parseInt(member.id)}</td>
                <td class="font-weight-bold">${escapeHtml(member.no_member)}</td>
                <td>${escapeHtml(member.nama)}</td>
                <td>${escapeHtml(member.no_hp || '-')}</td>
                <td>${escapeHtml(member.alamat || '-')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-member" data-id="${member.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-member" data-id="${member.id}">
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
                            <div class="font-weight-bold">${escapeHtml(member.nama)}</div>
                            <small class="text-muted">No Member: ${escapeHtml(member.no_member)}</small>
                        </div>
                        <span class="badge badge-info px-3 py-2">${escapeHtml(member.no_hp || 'Tanpa HP')}</span>
                    </div>
                    <div class="small text-muted mt-2">${escapeHtml(member.alamat || 'Alamat belum diisi')}</div>
                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-member" data-id="${member.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-member" data-id="${member.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    $('#member-table-body').html(tableHtml);
    $('#member-card-list').html(cardHtml);
}

function loadMembers() {
    $.get("<?= base_url('member/listData') ?>", function(response) {
        allMembers = response.data || [];
        renderMembers(allMembers);
    }, 'json');
}

function resetMemberForm() {
    $('#memberForm')[0].reset();
    $('#member-id').val('');
}

function openAddMemberModal() {
    memberMode = 'add';
    resetMemberForm();
    $('#memberModalTitle').text('Tambah Member');
    $('#memberModal').modal('show');
}

function openEditMemberModal(id) {
    const member = allMembers.find(item => parseInt(item.id) === parseInt(id));
    if (!member) return;

    memberMode = 'edit';
    resetMemberForm();

    $('#memberModalTitle').text('Edit Member');
    $('#member-id').val(member.id);
    $('#member-no').val(member.no_member);
    $('#member-name').val(member.nama);
    $('#member-phone').val(member.no_hp || '');
    $('#member-address').val(member.alamat || '');

    $('#memberModal').modal('show');
}

$(document).ready(function() {
    loadMembers();

    $('#btn-add-member').on('click', openAddMemberModal);

    $('#member-search').on('keyup', function() {
        const keyword = ($(this).val() || '').toLowerCase();
        const filtered = allMembers.filter(item =>
            (item.nama || '').toLowerCase().includes(keyword) ||
            (item.no_member || '').toLowerCase().includes(keyword) ||
            (item.no_hp || '').toLowerCase().includes(keyword)
        );
        renderMembers(filtered);
    });

    $(document).on('click', '.btn-edit-member', function() {
        openEditMemberModal($(this).data('id'));
    });

    $(document).on('click', '.btn-delete-member', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus member?',
            text: 'Data member yang sudah dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "<?= base_url('member/delete') ?>/" + id,
                type: 'POST',
                data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: 'json',
                success: function(response) {
                    Swal.fire('Berhasil', response.msg, 'success');
                    loadMembers();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.msg
                        ? xhr.responseJSON.msg
                        : 'Gagal menghapus member.';
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        });
    });

    $('#memberForm').on('submit', function(e) {
        e.preventDefault();

        const url = memberMode === 'add'
            ? "<?= base_url('member/save') ?>"
            : "<?= base_url('member/update') ?>";

        const $btn = $('#btn-save-member');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('#memberModal').modal('hide');
                Swal.fire('Berhasil', response.msg, 'success');
                loadMembers();
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
