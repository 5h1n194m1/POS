<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$avatarUrl = !empty($user['avatar'])
    ? base_url($user['avatar'])
    : 'https://ui-avatars.com/api/?name=' . urlencode($user['fullname'] ?? 'User') . '&background=random';
?>

<style>
    .profile-hero-card {
        position: relative;
        overflow: hidden;
        border: 0 !important;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.16), transparent 24%),
            linear-gradient(135deg, #1f3b73 0%, #2c4e8d 42%, #5f83be 100%) !important;
        color: #fff;
    }

    .profile-hero-card::before {
        content: '';
        position: absolute;
        inset: auto -50px -90px auto;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: rgba(255,255,255,.08);
    }

    .profile-summary-card {
        position: sticky;
        top: 78px;
    }

    .profile-avatar-shell {
        width: 132px;
        height: 132px;
        padding: 6px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,.96) 0%, rgba(255,255,255,.38) 100%);
        box-shadow: 0 20px 38px rgba(15, 23, 42, 0.18);
    }

    .profile-avatar-shell img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.74);
    }

    .profile-chip-row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
    }

    .profile-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.14);
        color: #f8fafc;
        font-size: .82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .profile-mini-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .profile-mini-stat {
        padding: 14px;
        border-radius: 18px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.10);
        text-align: left;
    }

    .profile-mini-stat-label {
        font-size: .74rem;
        text-transform: uppercase;
        letter-spacing: .45px;
        color: rgba(255,255,255,.72);
        margin-bottom: 6px;
    }

    .profile-mini-stat-value {
        font-weight: 700;
        line-height: 1.45;
        word-break: break-word;
    }

    .profile-upload-box {
        padding: 16px;
        border-radius: 18px;
        background: rgba(255,255,255,.10);
        border: 1px dashed rgba(255,255,255,.24);
        text-align: left;
    }

    .profile-upload-box .form-control-file {
        color: #fff;
    }

    .profile-upload-box .small {
        color: rgba(255,255,255,.76) !important;
    }

    .profile-detail-box {
        margin-top: 18px;
        padding: 16px;
        border-radius: 18px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.10);
        text-align: left;
    }

    .profile-detail-item + .profile-detail-item {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,.10);
    }

    .profile-detail-label {
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: rgba(255,255,255,.7);
        margin-bottom: 4px;
    }

    .profile-detail-value {
        color: #fff;
        font-weight: 600;
        word-break: break-word;
    }

    .profile-panel {
        border: 0 !important;
        overflow: hidden;
    }

    .profile-panel .card-header {
        background: linear-gradient(180deg, rgba(248,250,252,.92) 0%, rgba(255,255,255,.78) 100%) !important;
    }

    .profile-input-muted {
        background: linear-gradient(180deg, rgba(248,250,252,.98) 0%, rgba(241,245,249,.88) 100%) !important;
    }

    body.dark-mode .profile-panel .card-header {
        background: linear-gradient(180deg, rgba(36,48,65,.95) 0%, rgba(31,41,55,.88) 100%) !important;
    }

    @media (max-width: 991.98px) {
        .profile-summary-card {
            position: static;
            top: auto;
        }

        .profile-mini-stats {
            grid-template-columns: 1fr;
        }

        .profile-avatar-shell {
            width: 116px;
            height: 116px;
        }

        #form-upload-avatar .d-flex,
        #form-profile-update .text-right,
        #form-change-password .text-right {
            display: block !important;
        }

        #form-upload-avatar .btn,
        #form-profile-update .btn,
        #form-change-password .btn {
            width: 100%;
            margin-right: 0 !important;
            margin-top: 10px;
        }

        #form-upload-avatar .btn:first-child {
            margin-top: 0;
        }
    }
</style>

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
        <div class="card shadow-sm profile-summary-card profile-hero-card">
            <div class="card-body text-center p-4">
                <div class="profile-avatar-shell">
                    <img
                        id="profile-avatar"
                        src="<?= $avatarUrl ?>"
                        alt="Avatar"
                    >
                </div>

                <h3 class="font-weight-bold mb-2 profile-fullname"><?= esc($user['fullname'] ?? '-') ?></h3>
                <p class="mb-3" style="color:rgba(255,255,255,.82);"><?= esc($user['email'] ?? '-') ?></p>

                <div class="profile-chip-row">
                    <span class="profile-chip"><i class="fas fa-user-shield"></i> <?= esc($user['role'] ?? 'guest') ?></span>
                    <span class="profile-chip"><i class="fas fa-check-circle"></i> <?= esc($user['status'] ?? '-') ?></span>
                </div>

                <div class="profile-mini-stats">
                    <div class="profile-mini-stat">
                        <div class="profile-mini-stat-label">Username</div>
                        <div class="profile-mini-stat-value"><?= esc($user['username'] ?? '-') ?></div>
                    </div>
                    <div class="profile-mini-stat">
                        <div class="profile-mini-stat-label">Last Login</div>
                        <div class="profile-mini-stat-value"><?= !empty($user['last_login']) ? date('d M Y H:i', strtotime($user['last_login'])) : 'Belum tercatat' ?></div>
                    </div>
                </div>

                <form id="form-upload-avatar" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="profile-upload-box">
                        <div class="font-weight-bold mb-2">Foto Profil</div>
                        <div class="small mb-3">Upload foto baru agar akun terlihat lebih personal. Format jpg, jpeg, png, webp. Maksimal 2 MB.</div>
                        <input
                            type="file"
                            name="avatar"
                            id="avatar-input"
                            class="form-control-file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >
                        <small class="d-block mt-2 text-white" id="avatar-file-name"></small>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-light btn-sm mr-2" id="btn-upload-avatar">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>

                        <button type="button" class="btn btn-outline-light btn-sm" id="btn-delete-avatar">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </form>

                <div class="profile-detail-box">
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">Email Aktif</div>
                        <div class="profile-detail-value"><?= esc($user['email'] ?? '-') ?></div>
                    </div>
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">Status Akun</div>
                        <div class="profile-detail-value"><?= esc($user['status'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-outline card-success shadow-sm profile-panel">
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
                                <input type="text" class="form-control profile-input-muted" value="<?= esc($user['username'] ?? '') ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-control profile-input-muted text-uppercase" value="<?= esc($user['role'] ?? '') ?>" readonly>
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

        <div class="card card-outline card-danger shadow-sm profile-panel">
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
