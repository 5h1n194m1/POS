<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2b2118">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Login - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top right, rgba(196, 161, 107, 0.18), transparent 28%),
                radial-gradient(circle at bottom left, rgba(120, 98, 74, 0.12), transparent 22%),
                linear-gradient(180deg, #f7f2ea 0%, #efe7db 42%, #e6ddd2 100%);
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
            max-width: 420px;
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

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 12px;
            background: rgba(255, 244, 230, 0.10);
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .2px;
            animation: authFadeIn .45s ease both;
            border: 1px solid rgba(230, 206, 173, 0.22);
        }

        .auth-title {
            font-size: 1.7rem;
            font-weight: 800;
            margin: 16px 0 8px;
        }

        .auth-subtitle {
            margin: 0;
            font-size: .95rem;
            opacity: .95;
            line-height: 1.55;
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

        .btn-primary {
            min-height: 52px;
            border-radius: 16px;
            font-weight: 700;
            background: linear-gradient(135deg, #8f6a3b 0%, #b5915e 100%);
            border: 0;
            box-shadow: 0 12px 24px rgba(143, 106, 59, 0.22);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .auth-footer-link {
            font-size: .95rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(143, 106, 59, 0.25);
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

        @keyframes authFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 575.98px) {
            .auth-shell {
                padding: 12px;
                align-items: stretch;
            }

            .auth-card {
                max-width: 100%;
                border-radius: 22px;
            }

            .auth-hero,
            .auth-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .auth-title {
                font-size: 1.45rem;
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
            <div class="auth-badge">POS Mobile Kasir</div>
            <div class="auth-title">Masuk ke aplikasi</div>
            <p class="auth-subtitle">Dibuat agar nyaman dipakai di HP. Login dulu, lalu langsung mulai transaksi dengan tampilan yang sederhana.</p>
        </div>

        <div class="auth-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('msg')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('msg')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label for="login" class="form-label">Username atau Email</label>
                    <input
                        type="text"
                        name="login"
                        class="form-control"
                        id="login"
                        value="<?= old('login') ?>"
                        placeholder="Masukkan username atau email"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        id="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Masuk Sekarang</button>
                </div>
            </form>

            <hr class="my-4">
            <div class="text-center auth-footer-link">
                <span class="text-muted">Belum punya akun?</span>
                <a href="<?= base_url('register') ?>" class="text-decoration-none fw-semibold">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
