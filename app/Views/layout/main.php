<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-height: 100vh; background: #212529; }
        .nav-link { color: #adb5bd; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: white; background: #343a40; border-radius: 8px; }
        @media (max-width: 768px) { .sidebar { min-height: auto; } }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3 shadow">
            <div class="text-white text-center mb-4">
                <h4 class="fw-bold"><i class="bi bi-cart4"></i> POS SAYA</h4>
                <small class="text-muted">Versi 1.0 (Android Ready)</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link <?= (uri_string() == 'product') ? 'active' : '' ?>" href="<?= base_url('product') ?>">
                        <i class="bi bi-box-seam me-2"></i> Data Produk
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="#">
                        <i class="bi bi-receipt me-2"></i> Transaksi Kasir
                    </a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('logout') ?>" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

.sidebar {
    position: relative;
    z-index: 1000; /* Memastikan menu ada di lapisan paling atas */
}