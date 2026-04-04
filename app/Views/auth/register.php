<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2b2118">
    <title>Register - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(196, 161, 107, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(120, 98, 74, 0.12), transparent 24%),
                linear-gradient(180deg, #f8f3eb 0%, #efe8dd 48%, #e6ddd2 100%);
            color: #0f172a;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .auth-card {
            width: 100%;
            max-width: 470px;
            border: 0;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(43, 33, 24, 0.14);
            background: rgba(255, 251, 247, 0.96);
            backdrop-filter: blur(10px);
            animation: authSlideUp .55s ease both;
            border: 1px solid rgba(146, 120, 90, 0.14);
        }

        .auth-hero {
            padding: 22px 22px 14px;
            background: linear-gradient(135deg, #2b2118 0%, #413126 55%, #6a5240 100%);
            color: #f7efe5;
        }

        .auth-title {
            font-size: 1.65rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: .94rem;
            line-height: 1.55;
            margin: 0;
            opacity: .95;
        }

        .auth-body {
            padding: 22px;
        }

        .form-label {
            font-weight: 700;
            font-size: .92rem;
            color: #4a382a;
        }

        .form-control {
            min-height: 52px;
            border-radius: 16px;
            padding-left: 16px;
            padding-right: 16px;
            border-color: #d8c7b2;
            background: rgba(255, 252, 248, 0.96);
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(181, 145, 94, 0.14);
            border-color: #b5915e;
        }

        .btn-success {
            min-height: 52px;
            border-radius: 16px;
            font-weight: 700;
            border: 0;
            background: linear-gradient(135deg, #8f6a3b 0%, #b5915e 100%);
            box-shadow: 0 12px 24px rgba(143, 106, 59, 0.2);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(143, 106, 59, 0.24);
        }

        @keyframes authSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 575.98px) {
            .auth-shell {
                padding: 12px;
            }

            .auth-card {
                border-radius: 22px;
            }

            .auth-hero,
            .auth-body {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="card auth-card">
        <div class="auth-hero">
            <div class="auth-title">Daftar akun baru</div>
            <p class="auth-subtitle">Isi data dengan sederhana, lalu akun siap dipakai dari HP untuk operasional harian.</p>
        </div>

        <div class="auth-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('register') ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="fullname" class="form-control" value="<?= old('fullname') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Sudah punya akun? <a href="<?= base_url('login') ?>" class="fw-semibold text-decoration-none">Login</a>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
