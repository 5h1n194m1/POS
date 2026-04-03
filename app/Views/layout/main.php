<?php
$currentUri = uri_string();

$fullName = session()->get('fullname') ?: 'User';
$role     = session()->get('role') ?: 'guest';
$avatar   = session()->get('avatar');

$isAdmin     = ($role === 'admin');
$isDashboard = ($currentUri === 'dashboard');
$isProduct   = (strpos($currentUri, 'product') === 0);
$isKasir     = (strpos($currentUri, 'kasir') === 0);
$isLaporan   = ($currentUri === 'laporan' || strpos($currentUri, 'laporan-penjualan') === 0);
$isRiwayat   = (strpos($currentUri, 'riwayat-transaksi') === 0);
$isUser      = (strpos($currentUri, 'user') === 0);
$isProfile   = (strpos($currentUri, 'profile') === 0);

$avatarUrl = !empty($avatar)
    ? base_url($avatar)
    : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=random';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'POS SAYA') ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --sidebar-width: 250px;
            --topbar-height: 60px;
            --mobile-bottom-nav-height: 66px;
            --app-bg: #f4f6f9;
            --card-radius: 14px;
        }

        html, body {
            background: var(--app-bg);
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .wrapper {
            min-height: 100vh;
        }

        .main-header {
            height: var(--topbar-height);
            border-bottom: 1px solid #e5e7eb !important;
            background: #ffffff !important;
            margin-left: var(--sidebar-width) !important;
            transition: margin-left .2s ease-in-out !important;
            z-index: 1035;
        }

        .main-sidebar {
            width: var(--sidebar-width) !important;
            margin-left: 0 !important;
            background: #111827 !important;
            transition: transform .2s ease-in-out, margin-left .2s ease-in-out !important;
            z-index: 1040;
        }

        .main-sidebar .brand-link {
            border-bottom: 1px solid rgba(255,255,255,.08) !important;
        }

        .main-sidebar .brand-text {
            color: #fff !important;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .nav-sidebar .nav-header {
            color: #9ca3af !important;
            font-size: 11px;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding-top: 14px;
        }

        .nav-sidebar .nav-link {
            border-radius: 10px;
            margin: 2px 8px;
        }

        .nav-sidebar .nav-link p {
            color: #d1d5db !important;
            font-weight: 600;
        }

        .nav-sidebar .nav-link .nav-icon {
            color: #d1d5db !important;
        }

        .nav-sidebar .nav-link.active,
        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,.10) !important;
        }

        .nav-sidebar .nav-link.active p,
        .nav-sidebar .nav-link:hover p,
        .nav-sidebar .nav-link.active .nav-icon,
        .nav-sidebar .nav-link:hover .nav-icon {
            color: #ffffff !important;
        }

        .content-wrapper {
            background: var(--app-bg) !important;
            margin-left: var(--sidebar-width) !important;
            min-height: calc(100vh - var(--topbar-height)) !important;
            transition: margin-left .2s ease-in-out !important;
            padding-bottom: 24px;
        }

        .main-footer {
            margin-left: var(--sidebar-width) !important;
            transition: margin-left .2s ease-in-out !important;
            font-size: 13px;
            background: #fff !important;
            border-top: 1px solid #e5e7eb;
        }

        body.sidebar-collapse .main-header,
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-footer {
            margin-left: 0 !important;
        }

        body.sidebar-collapse .main-sidebar {
            margin-left: calc(var(--sidebar-width) * -1) !important;
        }

        .content {
            padding-top: 12px;
        }

        .card,
        .small-box,
        .info-box {
            border-radius: var(--card-radius) !important;
        }

        .card {
            border: 1px solid #e5e7eb;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05) !important;
        }

        .table-responsive {
            border-radius: 12px;
        }

        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .top-user-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-user-meta {
            text-align: right;
            line-height: 1.1;
        }

        .top-user-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .top-user-role {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .top-user-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
        }

        .mobile-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 1039;
        }

        .mobile-bottom-nav {
            display: none;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

        /* DARK MODE */
        body.dark-mode {
            background: #111827 !important;
        }

        body.dark-mode .main-header,
        body.dark-mode .main-footer,
        body.dark-mode .card,
        body.dark-mode .modal-content,
        body.dark-mode .dropdown-menu,
        body.dark-mode .content-wrapper {
            background: #1f2937 !important;
            color: #f9fafb !important;
        }

        body.dark-mode .content-wrapper {
            background: #111827 !important;
        }

        body.dark-mode .main-header {
            border-bottom-color: #374151 !important;
        }

        body.dark-mode .main-footer {
            border-top-color: #374151 !important;
        }

        body.dark-mode .top-user-name,
        body.dark-mode .navbar-light .navbar-nav .nav-link,
        body.dark-mode .main-header .nav-link {
            color: #f9fafb !important;
        }

        body.dark-mode .card-header,
        body.dark-mode .modal-header {
            background: #243041 !important;
            border-bottom: 1px solid #374151 !important;
            color: #fff !important;
        }

        body.dark-mode .table {
            color: #f9fafb !important;
        }

        body.dark-mode .table thead th,
        body.dark-mode .thead-light th,
        body.dark-mode .table-bordered thead th,
        body.dark-mode .table-head-fixed thead th {
            background: #243041 !important;
            color: #ffffff !important;
            border-color: #374151 !important;
        }

        body.dark-mode .table td,
        body.dark-mode .table th,
        body.dark-mode .table-bordered td,
        body.dark-mode .table-bordered th {
            border-color: #374151 !important;
        }

        body.dark-mode .form-control,
        body.dark-mode .custom-select,
        body.dark-mode .input-group-text,
        body.dark-mode .select2-container--bootstrap4 .select2-selection {
            background: #111827 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
        }

        body.dark-mode .dropdown-item {
            color: #f9fafb !important;
        }

        body.dark-mode .dropdown-item:hover {
            background: #243041 !important;
        }

        body.dark-mode .text-muted {
            color: #9ca3af !important;
        }

        /* MOBILE */
        @media (max-width: 991.98px) {
            .main-header,
            .content-wrapper,
            .main-footer {
                margin-left: 0 !important;
            }

            .main-sidebar {
                transform: translateX(-100%);
                margin-left: 0 !important;
                position: fixed !important;
                top: 0;
                bottom: 0;
            }

            body.mobile-sidebar-open .main-sidebar {
                transform: translateX(0);
            }

            body.mobile-sidebar-open .mobile-sidebar-overlay {
                display: block;
            }

            .main-header {
                height: 58px;
            }

            .content-wrapper {
                min-height: calc(100vh - 58px) !important;
                padding-bottom: calc(var(--mobile-bottom-nav-height) + 12px);
            }

            .content {
                padding-top: 6px;
            }

            .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .card {
                border-radius: 12px !important;
            }

            .main-footer {
                display: none;
            }

            .top-user-name {
                font-size: 14px;
            }

            .top-user-role {
                font-size: 10px;
            }

            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                height: var(--mobile-bottom-nav-height);
                background: #ffffff;
                border-top: 1px solid #e5e7eb;
                z-index: 1045;
                padding-bottom: env(safe-area-inset-bottom);
            }

            .mobile-bottom-nav a {
                flex: 1;
                text-decoration: none;
                color: #6b7280;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 600;
                gap: 3px;
            }

            .mobile-bottom-nav a i {
                font-size: 18px;
            }

            .mobile-bottom-nav a.active {
                color: #2563eb;
            }

            body.dark-mode .mobile-bottom-nav {
                background: #1f2937 !important;
                border-top-color: #374151 !important;
            }

            body.dark-mode .mobile-bottom-nav a {
                color: #9ca3af !important;
            }

            body.dark-mode .mobile-bottom-nav a.active {
                color: #60a5fa !important;
            }
        }
    </style>
</head>
<body class="hold-transition layout-fixed">
<div class="wrapper">

    <div id="mobileSidebarOverlay" class="mobile-sidebar-overlay"></div>

    <nav class="main-header navbar navbar-expand navbar-light shadow-sm">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#" id="sidebarToggle" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link font-weight-bold text-dark">POS Mobile Web</span>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-2">
                <a href="#" id="theme-toggle" class="btn btn-outline-secondary theme-toggle-btn" title="Ganti tema">
                    <i id="theme-toggle-icon" class="fas fa-moon"></i>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                    <div class="top-user-box">
                        <div class="top-user-meta d-none d-md-block">
                            <div class="top-user-name" id="nav-user-name"><?= esc($fullName) ?></div>
                            <div><span class="badge badge-primary top-user-role"><?= strtoupper(esc($role)) ?></span></div>
                        </div>
                        <img id="nav-user-avatar" src="<?= $avatarUrl ?>" alt="avatar" class="top-user-avatar">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                    <a href="<?= base_url('profile') ?>" class="dropdown-item">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url('logout') ?>" class="dropdown-item text-danger" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url('dashboard') ?>" class="brand-link text-center">
            <span class="brand-text">POS SAYA</span>
        </a>

        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-header">Menu Utama</li>

                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= $isDashboard ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('kasir') ?>" class="nav-link <?= $isKasir ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>

                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('product') ?>" class="nav-link <?= $isProduct ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('riwayat-transaksi') ?>" class="nav-link <?= $isRiwayat ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Transaksi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan-penjualan') ?>" class="nav-link <?= $isLaporan ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('user') ?>" class="nav-link <?= $isUser ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Karyawan</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-header">Sistem</li>

                    <li class="nav-item">
                        <a href="<?= base_url('profile') ?>" class="nav-link <?= $isProfile ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('logout') ?>" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>POS SAYA</strong> &copy; 2026
    </footer>

    <nav class="mobile-bottom-nav d-lg-none">
        <a href="<?= base_url('dashboard') ?>" class="<?= $isDashboard ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="<?= base_url('kasir') ?>" class="<?= $isKasir ? 'active' : '' ?>">
            <i class="fas fa-cash-register"></i>
            <span>Kasir</span>
        </a>
        <?php if ($isAdmin): ?>
        <a href="<?= base_url('product') ?>" class="<?= $isProduct ? 'active' : '' ?>">
            <i class="fas fa-boxes"></i>
            <span>Barang</span>
        </a>
        <a href="<?= base_url('riwayat-transaksi') ?>" class="<?= $isRiwayat ? 'active' : '' ?>">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
        <?php endif; ?>
        <a href="<?= base_url('profile') ?>" class="<?= $isProfile ? 'active' : '' ?>">
            <i class="fas fa-user-circle"></i>
            <span>Profil</span>
        </a>
    </nav>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function isMobileViewport() {
        return window.innerWidth < 992;
    }

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        $('body').toggleClass('dark-mode', isDark);
        $('#theme-toggle-icon')
            .toggleClass('fa-moon', !isDark)
            .toggleClass('fa-sun', isDark);
    }

    function openMobileSidebar() {
        $('body').addClass('mobile-sidebar-open');
    }

    function closeMobileSidebar() {
        $('body').removeClass('mobile-sidebar-open');
    }

    function toggleDesktopSidebar() {
        $('body').toggleClass('sidebar-collapse');
        const collapsed = $('body').hasClass('sidebar-collapse') ? '1' : '0';
        localStorage.setItem('pos_sidebar_collapsed', collapsed);
    }

    $(document).ready(function() {
        const savedTheme = localStorage.getItem('pos_theme') || 'light';
        applyTheme(savedTheme);

        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih --',
            allowClear: true
        });

        if (!isMobileViewport()) {
            const savedSidebar = localStorage.getItem('pos_sidebar_collapsed');
            if (savedSidebar === '1') {
                $('body').addClass('sidebar-collapse');
            }
        }

        $('#theme-toggle').on('click', function(e) {
            e.preventDefault();
            const nextTheme = $('body').hasClass('dark-mode') ? 'light' : 'dark';
            localStorage.setItem('pos_theme', nextTheme);
            applyTheme(nextTheme);
        });

        $('#sidebarToggle').on('click', function(e) {
            e.preventDefault();

            if (isMobileViewport()) {
                if ($('body').hasClass('mobile-sidebar-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                toggleDesktopSidebar();
            }
        });

        $('#mobileSidebarOverlay').on('click', function() {
            closeMobileSidebar();
        });

        $('.main-sidebar a').on('click', function() {
            if (isMobileViewport()) {
                closeMobileSidebar();
            }
        });

        $(window).on('resize', function() {
            if (!isMobileViewport()) {
                closeMobileSidebar();
            }
        });
    });
</script>

<?= $this->renderSection('script') ?>

</body>
</html>