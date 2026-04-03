<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .login-card { margin-top: 100px; max-width: 400px; border: none; border-radius: 15px; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card shadow login-card w-100">
        <div class="card-body p-4">
            <h3 class="text-center fw-bold mb-3">POS KASIR</h3>
            <p class="text-muted text-center mb-4">Silakan login untuk masuk ke sistem</p>

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
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        id="password"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary py-2">Masuk Sekarang</button>
                </div>
            </form>

            <hr class="my-4">
            <div class="text-center">
                <span class="text-muted">Belum punya akun?</span>
                <a href="<?= base_url('register') ?>" class="text-decoration-none">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>