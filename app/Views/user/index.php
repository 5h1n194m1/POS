<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Management User</h1>
                <small class="text-muted">Admin dapat mengelola akun karyawan dan role sistem.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <button class="btn btn-primary" id="btn-add-user">
                    <i class="fas fa-user-plus mr-1"></i> Tambah User
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="user-search" class="form-control" placeholder="Cari username / nama / email...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Memuat data user...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-md-none p-3" id="user-card-list">
            <div class="text-center text-muted py-4">Memuat data user...</div>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="userForm" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="user-id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userModalTitle">Tambah User</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span style="color:#fff;">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="user-username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="fullname" id="user-fullname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="user-email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password <small class="text-muted">(kosongkan saat edit jika tidak diubah)</small></label>
                    <input type="password" name="password" id="user-password" class="form-control">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="user-role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label>Status</label>
                    <select name="status" id="user-status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn-save-user">
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
let allUsers = [];
let userMode = 'add';

function escapeHtml(text) {
    return $('<div>').text(text ?? '').html();
}

function renderUsers(rows) {
    if (!rows.length) {
        $('#user-table-body').html(`
            <tr>
                <td colspan="10" class="text-center py-4 text-muted">Tidak ada data user.</td>
            </tr>
        `);
        $('#user-card-list').html(`
            <div class="text-center py-4 text-muted">Tidak ada data user.</div>
        `);
        return;
    }

    let html = '';
    let cardHtml = '';
    rows.forEach(user => {
        const avatar = user.avatar
            ? "<?= base_url() ?>/" + user.avatar
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.fullname || 'User') + '&background=random';

        html += `
            <tr>
                <td>${parseInt(user.id)}</td>
                <td><img src="${avatar}" width="36" height="36" style="border-radius:50%;object-fit:cover;"></td>
                <td class="font-weight-bold">${escapeHtml(user.username)}</td>
                <td>${escapeHtml(user.fullname || '-')}</td>
                <td>${escapeHtml(user.email || '-')}</td>
                <td><span class="badge badge-primary text-uppercase">${escapeHtml(user.role || '-')}</span></td>
                <td>
                    ${user.status === 'aktif'
                        ? '<span class="badge badge-success">Aktif</span>'
                        : '<span class="badge badge-secondary">Non-Aktif</span>'}
                </td>
                <td>${user.last_login || '-'}</td>
                <td>${user.created_at || '-'}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-user" data-id="${user.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" data-id="${user.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        cardHtml += `
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <img src="${avatar}" width="48" height="48" style="border-radius:50%;object-fit:cover;" class="mr-3">
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">${escapeHtml(user.fullname || '-')}</div>
                            <div class="small text-muted">@${escapeHtml(user.username || '-')}</div>
                            <div class="small text-muted">${escapeHtml(user.email || '-')}</div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex flex-wrap">
                        <span class="badge badge-primary text-uppercase mr-2 mb-2">${escapeHtml(user.role || '-')}</span>
                        ${user.status === 'aktif'
                            ? '<span class="badge badge-success mb-2">Aktif</span>'
                            : '<span class="badge badge-secondary mb-2">Non-Aktif</span>'}
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Last Login</small>
                            <span>${escapeHtml(user.last_login || '-')}</span>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted d-block">Dibuat</small>
                            <span>${escapeHtml(user.created_at || '-')}</span>
                        </div>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-user" data-id="${user.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" data-id="${user.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    $('#user-table-body').html(html);
    $('#user-card-list').html(cardHtml);
}

function loadUsers() {
    $.get("<?= base_url('user/listData') ?>", function(response) {
        allUsers = response.data || [];
        renderUsers(allUsers);
    }, 'json');
}

function resetUserForm() {
    $('#userForm')[0].reset();
    $('#user-id').val('');
}

function openAddUserModal() {
    userMode = 'add';
    resetUserForm();
    $('#userModalTitle').text('Tambah User');
    $('#user-password').prop('required', true);
    $('#userModal').modal('show');
}

function openEditUserModal(id) {
    const user = allUsers.find(item => parseInt(item.id) === parseInt(id));
    if (!user) return;

    userMode = 'edit';
    resetUserForm();

    $('#userModalTitle').text('Edit User');
    $('#user-id').val(user.id);
    $('#user-username').val(user.username);
    $('#user-fullname').val(user.fullname);
    $('#user-email').val(user.email);
    $('#user-role').val(user.role);
    $('#user-status').val(user.status);
    $('#user-password').prop('required', false);

    $('#userModal').modal('show');
}

$(document).ready(function() {
    loadUsers();

    $('#btn-add-user').on('click', function() {
        openAddUserModal();
    });

    $('#user-search').on('keyup', function() {
        const keyword = $(this).val().toLowerCase();

        const filtered = allUsers.filter(user => {
            return (user.username || '').toLowerCase().includes(keyword)
                || (user.fullname || '').toLowerCase().includes(keyword)
                || (user.email || '').toLowerCase().includes(keyword);
        });

        renderUsers(filtered);
    });

    $(document).on('click', '.btn-edit-user', function() {
        openEditUserModal($(this).data('id'));
    });

    $(document).on('click', '.btn-delete-user', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus user?',
            text: 'Data user yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "<?= base_url('user/delete') ?>/" + id,
                type: "POST",
                data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: "json",
                success: function(response) {
                    Swal.fire('Berhasil', response.msg, 'success');
                    loadUsers();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.msg
                        ? xhr.responseJSON.msg
                        : 'Gagal menghapus user.';
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        });
    });

    $('#userForm').on('submit', function(e) {
        e.preventDefault();

        const url = userMode === 'add'
            ? "<?= base_url('user/save') ?>"
            : "<?= base_url('user/update') ?>";

        const $btn = $('#btn-save-user');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('#userModal').modal('hide');
                Swal.fire('Berhasil', response.msg, 'success');
                loadUsers();
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
