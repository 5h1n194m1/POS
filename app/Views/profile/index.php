<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$avatarUrl = !empty($user['avatar'])
    ? base_url($user['avatar'])
    : 'https://ui-avatars.com/api/?name=' . urlencode($user['fullname'] ?? 'User') . '&background=random';
?>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Profil Saya</h1>
                <small class="text-muted">Kelola data akun, password, dan foto profil Anda.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body text-center">
                <img
                    id="profile-avatar"
                    src="<?= $avatarUrl ?>"
                    class="img-circle elevation-2 mb-3"
                    width="110"
                    height="110"
                    alt="Avatar"
                    style="object-fit: cover;"
                >

                <h4 class="font-weight-bold profile-fullname"><?= esc($user['fullname'] ?? '-') ?></h4>
                <p class="text-muted mb-2"><?= esc($user['email'] ?? '-') ?></p>
                <span class="badge badge-primary text-uppercase"><?= esc($user['role'] ?? 'guest') ?></span>
                <span class="badge badge-success ml-1"><?= esc($user['status'] ?? '-') ?></span>

                <hr>

                <form id="form-upload-avatar" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group text-left">
                        <label>Upload Foto Profil</label>
                        <input
                            type="file"
                            name="avatar"
                            id="avatar-input"
                            class="form-control-file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >
                        <small class="text-muted d-block mt-2">
                            Format: jpg, jpeg, png, webp. Maksimal 2 MB.
                        </small>
                        <small class="text-primary d-block mt-1" id="avatar-file-name"></small>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-sm mr-2" id="btn-upload-avatar">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>

                        <button type="button" class="btn btn-outline-danger btn-sm" id="btn-delete-avatar">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-left">
                    <p class="mb-2">
                        <strong>Username:</strong><br>
                        <span class="text-muted"><?= esc($user['username'] ?? '-') ?></span>
                    </p>
                    <p class="mb-0">
                        <strong>Last Login:</strong><br>
                        <span class="text-muted"><?= !empty($user['last_login']) ? date('d M Y H:i', strtotime($user['last_login'])) : 'Belum tercatat' ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-outline card-success shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-user-edit mr-2"></i>Informasi Akun</h3>
            </div>
            <div class="card-body">
                <form id="form-profile-update">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control" value="<?= esc($user['fullname'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="<?= esc($user['username'] ?? '') ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-control text-uppercase" value="<?= esc($user['role'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success" id="btn-save-profile">
                            <i class="fas fa-save mr-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-danger shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-lock mr-2"></i>Ubah Password</h3>
            </div>
            <div class="card-body">
                <form id="form-change-password">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-danger" id="btn-save-password">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
function ajaxErrorMessage(xhr, fallbackMessage) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
        return xhr.responseJSON.message;
    }
    return fallbackMessage;
}

function updateAvatarUI(url) {
    $('#profile-avatar').attr('src', url);
    $('#nav-user-avatar').attr('src', url);
}

$(document).ready(function() {
    $('#avatar-input').on('change', function() {
        const file = this.files && this.files[0] ? this.files[0] : null;
        $('#avatar-file-name').text(file ? file.name : '');
    });

    $('#form-profile-update').on('submit', function(e) {
        e.preventDefault();

        const $btn = $('#btn-save-profile');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: "<?= base_url('profile/update') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('#nav-user-name').text(response.fullname);
                $('.profile-fullname').text(response.fullname);
                updateAvatarUI(response.avatar_url);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: ajaxErrorMessage(xhr, 'Gagal memperbarui profil.')
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Profil');
            }
        });
    });

    $('#form-change-password').on('submit', function(e) {
        e.preventDefault();

        const $btn = $('#btn-save-password');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: "<?= base_url('profile/change-password') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('#form-change-password')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: ajaxErrorMessage(xhr, 'Gagal mengubah password.')
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-key mr-1"></i> Ubah Password');
            }
        });
    });

    $('#form-upload-avatar').on('submit', function(e) {
        e.preventDefault();

        const input = $('#avatar-input')[0];
        const file = input.files && input.files[0] ? input.files[0] : null;

        if (!file) {
            Swal.fire('Peringatan', 'Pilih file avatar terlebih dahulu.', 'warning');
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire('Format tidak didukung', 'Gunakan file JPG, JPEG, PNG, atau WEBP.', 'warning');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('File terlalu besar', 'Ukuran file maksimal 2 MB.', 'warning');
            return;
        }

        const formData = new FormData(this);
        const $btn = $('#btn-upload-avatar');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Upload...');

        $.ajax({
            url: "<?= base_url('profile/upload-avatar') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function(response) {
                updateAvatarUI(response.avatar_url);
                $('#avatar-input').val('');
                $('#avatar-file-name').text('');
                Swal.fire('Berhasil', response.message, 'success');
            },
            error: function(xhr) {
                Swal.fire('Gagal', ajaxErrorMessage(xhr, 'Gagal upload foto profil.'), 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload');
            }
        });
    });

    $('#btn-delete-avatar').on('click', function() {
        Swal.fire({
            title: 'Hapus foto profil?',
            text: 'Foto profil akan dikembalikan ke avatar default.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "<?= base_url('profile/delete-avatar') ?>",
                type: "POST",
                data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: "json",
                success: function(response) {
                    updateAvatarUI(response.avatar_url);
                    $('#avatar-input').val('');
                    $('#avatar-file-name').text('');
                    Swal.fire('Berhasil', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('Gagal', ajaxErrorMessage(xhr, 'Gagal menghapus foto profil.'), 'error');
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>