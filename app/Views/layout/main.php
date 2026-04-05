<?php
$currentUri = uri_string();

$fullName = session()->get('fullname') ?: 'User';
$role     = session()->get('role') ?: 'guest';
$avatar   = session()->get('avatar');

$isAdmin     = ($role === 'admin');
$isDashboard = ($currentUri === 'dashboard');
$isProduct   = (strpos($currentUri, 'product') === 0);
$isKategori  = (strpos($currentUri, 'kategori') === 0);
$isMember    = (strpos($currentUri, 'member') === 0);
$isKasir     = (strpos($currentUri, 'kasir') === 0);
$isLaporan   = ($currentUri === 'laporan' || strpos($currentUri, 'laporan-penjualan') === 0);
$isKeuntungan = (strpos($currentUri, 'laporan-keuntungan') === 0);
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
    <meta name="theme-color" content="#1d4ed8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
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
            --mobile-utility-height: 58px;
            --app-bg: #eef3f9;
            --app-surface: rgba(255, 255, 255, 0.88);
            --app-border: rgba(148, 163, 184, 0.18);
            --card-radius: 18px;
            --mobile-safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        html, body {
            background: var(--app-bg);
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            -webkit-tap-highlight-color: transparent;
            color: #0f172a;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.10), transparent 24%),
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.08), transparent 22%),
                linear-gradient(180deg, #f8fbff 0%, #eef3f9 100%);
        }

        .wrapper {
            min-height: 100vh;
            position: relative;
        }

        .main-header {
            height: var(--topbar-height);
            border-bottom: 1px solid rgba(226,232,240,.72) !important;
            background: rgba(255,255,255,.76) !important;
            backdrop-filter: blur(16px);
            margin-left: 0 !important;
            transition: background-color .25s ease, box-shadow .25s ease, opacity .18s ease !important;
            z-index: 1035;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            position: sticky;
            top: 0;
        }

        .main-sidebar {
            width: var(--sidebar-width) !important;
            margin-left: 0 !important;
            background:
                radial-gradient(circle at top, rgba(59,130,246,.22), transparent 28%),
                linear-gradient(180deg, #0f172a 0%, #111827 42%, #0b1220 100%) !important;
            transition: transform .22s ease-in-out, box-shadow .25s ease, opacity .22s ease !important;
            z-index: 1040;
            box-shadow: 20px 0 42px rgba(2, 6, 23, 0.24);
        }

        .main-sidebar .brand-link {
            border-bottom: 1px solid rgba(255,255,255,.08) !important;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .main-sidebar .brand-text {
            color: #fff !important;
            font-weight: 700;
            letter-spacing: .5px;
            font-size: 1.05rem;
        }

        .nav-sidebar .nav-header {
            color: #9ca3af !important;
            font-size: 11px;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding-top: 14px;
        }

        .nav-sidebar .nav-link {
            border-radius: 14px;
            margin: 4px 10px;
            padding-top: .82rem;
            padding-bottom: .82rem;
            transition: transform .18s ease, background-color .18s ease, box-shadow .18s ease;
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
            background: rgba(255,255,255,.12) !important;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06), 0 10px 18px rgba(15, 23, 42, 0.18);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-link.active p,
        .nav-sidebar .nav-link:hover p,
        .nav-sidebar .nav-link.active .nav-icon,
        .nav-sidebar .nav-link:hover .nav-icon {
            color: #ffffff !important;
        }

        .content-wrapper {
            background: transparent !important;
            margin-left: 0 !important;
            min-height: calc(100vh - var(--topbar-height)) !important;
            transition: opacity .18s ease !important;
            padding-bottom: 30px;
            position: relative;
            overflow-x: hidden;
            overflow-y: visible;
        }

        .content-wrapper::before,
        .content-wrapper::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
            filter: blur(2px);
            opacity: .8;
        }

        .content-wrapper::before {
            width: 320px;
            height: 320px;
            right: -120px;
            top: 40px;
            background: radial-gradient(circle, rgba(59,130,246,.12) 0%, rgba(59,130,246,0) 68%);
        }

        .content-wrapper::after {
            width: 260px;
            height: 260px;
            left: -90px;
            bottom: 10%;
            background: radial-gradient(circle, rgba(16,185,129,.10) 0%, rgba(16,185,129,0) 70%);
        }

        .main-footer {
            margin-left: 0 !important;
            transition: opacity .18s ease !important;
            font-size: 13px;
            background: rgba(255,255,255,.72) !important;
            backdrop-filter: blur(14px);
            border-top: 1px solid rgba(226,232,240,.7);
        }

        body.sidebar-collapse .main-header,
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-footer {
            margin-left: 0 !important;
        }

        body.sidebar-collapse .main-sidebar {
            margin-left: 0 !important;
        }

        .content {
            padding-top: 18px;
        }

        body.app-fullscreen .main-sidebar,
        body.app-fullscreen .main-footer,
        body.app-fullscreen .desktop-sidebar-hotspot,
        body.app-fullscreen .desktop-burger-hotspot,
        body.app-fullscreen .mobile-bottom-nav {
            display: none !important;
        }

        body.app-fullscreen.mobile-sidebar-open .main-sidebar {
            display: block !important;
        }

        body.app-fullscreen.mobile-sidebar-open .mobile-sidebar-overlay {
            display: block !important;
        }

        body.app-fullscreen.desktop-sidebar-open .main-sidebar,
        body.app-fullscreen.mobile-sidebar-open .main-sidebar {
            display: block !important;
        }

        body.app-fullscreen .main-header {
            box-shadow: none !important;
            background: rgba(255,255,255,.92) !important;
        }

        body.app-fullscreen .content-wrapper {
            min-height: calc(100vh - var(--topbar-height)) !important;
            padding-bottom: 12px !important;
        }

        body.app-fullscreen .content {
            padding-top: 10px;
        }

        body.app-fullscreen .page-shell {
            max-width: none !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .card,
        .small-box,
        .info-box {
            border-radius: var(--card-radius) !important;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background-color .22s ease;
        }

        .card {
            border: 1px solid var(--app-border);
            background: var(--app-surface);
            backdrop-filter: blur(10px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.07) !important;
        }

        .table-responsive {
            border-radius: 16px;
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.08);
        }

        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #475569 !important;
            padding: 0;
        }

        .fullscreen-toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #2563eb !important;
            padding: 0;
        }

        #sidebarToggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f172a !important;
            position: relative;
            z-index: 1042;
        }

        .burger-icon {
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            width: 20px;
        }

        .burger-icon span {
            display: block;
            width: 20px;
            height: 2.5px;
            border-radius: 999px;
            background: currentColor;
        }

        .theme-toggle-btn:hover,
        .fullscreen-toggle-btn:hover,
        .btn:hover {
            transform: translateY(-1px);
        }

        .topbar-utility {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            margin-right: 8px;
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
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.10);
        }

        .mobile-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 1034;
        }

        .mobile-bottom-nav {
            display: none;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem + 2px) !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px) !important;
        }

        .btn,
        .form-control,
        .custom-select,
        .input-group-text {
            min-height: 44px;
            border-radius: 14px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background-color .18s ease;
        }

        .modal-footer .btn,
        .btn-sm {
            min-height: 38px;
        }

        .form-control:focus,
        .custom-select:focus,
        .btn:focus,
        .btn.focus {
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.12);
        }

        .content-header h1,
        .content-header .h1 {
            word-break: break-word;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .content > .container-fluid {
            max-width: 1480px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .modal {
            z-index: 1060;
        }

        .modal-backdrop {
            z-index: 1055;
        }

        .modal-dialog {
            pointer-events: auto;
        }

        .enhanced-ui .page-shell > *:not(.modal) {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .42s ease, transform .42s ease;
        }

        .enhanced-ui .page-shell > *.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .card:hover,
        .small-box:hover,
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 42px rgba(15, 23, 42, 0.10) !important;
            border-color: rgba(96, 165, 250, 0.24);
        }

        .small-box .icon,
        .info-box .info-box-icon {
            transition: transform .24s ease, opacity .24s ease;
        }

        .small-box:hover .icon,
        .info-box:hover .info-box-icon {
            transform: scale(1.05);
            opacity: .95;
        }

        body.dark-mode {
            background:
                radial-gradient(circle at top left, rgba(59,130,246,.12), transparent 24%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%) !important;
        }

        /* DARK MODE */
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
            background: transparent !important;
        }

        body.dark-mode .main-header {
            border-bottom-color: #374151 !important;
            background: rgba(17, 24, 39, 0.76) !important;
        }

        body.dark-mode .main-footer {
            border-top-color: #374151 !important;
            background: rgba(17, 24, 39, 0.72) !important;
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

        body.dark-mode .topbar-utility {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        body.dark-mode .theme-toggle-btn {
            color: #cbd5e1 !important;
        }

        body.dark-mode .fullscreen-toggle-btn {
            color: #60a5fa !important;
        }

        body.dark-mode #sidebarToggle {
            color: #f8fafc !important;
            background: rgba(17, 24, 39, 0.94) !important;
            border-color: rgba(71, 85, 105, 0.65) !important;
        }

        body.dark-mode.app-fullscreen .main-header {
            background: rgba(17, 24, 39, 0.92) !important;
        }

        body.dark-mode .card {
            border-color: rgba(71, 85, 105, 0.55) !important;
            box-shadow: 0 18px 38px rgba(2, 6, 23, 0.35) !important;
        }

        body.dark-mode .table-responsive {
            box-shadow: inset 0 0 0 1px rgba(71, 85, 105, 0.32);
        }

        @media (min-width: 992px) {
            .main-sidebar {
                position: fixed !important;
                top: 0;
                bottom: 0;
                left: 0;
                transform: translateX(calc(-100% - 18px));
                opacity: 0;
                pointer-events: none;
            }

            body.desktop-sidebar-open .main-sidebar {
                transform: translateX(0);
                opacity: 1;
                pointer-events: auto;
            }

            .main-header,
            .content-wrapper,
            .main-footer {
                transition: margin-left .22s ease, opacity .18s ease !important;
            }

            body.desktop-sidebar-open .main-header,
            body.desktop-sidebar-open .content-wrapper,
            body.desktop-sidebar-open .main-footer {
                margin-left: var(--sidebar-width) !important;
            }

            #sidebarToggle {
                position: relative;
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.92);
                border: 1px solid rgba(226, 232, 240, 0.9);
                box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
                opacity: 1;
                transform: none;
                transition: opacity .18s ease, transform .18s ease, box-shadow .18s ease;
            }

            #sidebarToggle:hover {
                box-shadow: 0 16px 28px rgba(15, 23, 42, 0.16);
            }

            .main-header .navbar-nav {
                align-items: center;
            }

            .content-header {
                margin-bottom: 1rem;
            }

            .content-header .row {
                align-items: center;
            }

            .content-header h1 {
                font-size: 1.9rem;
                letter-spacing: -.02em;
            }

            .content-header small {
                font-size: .94rem;
            }

            .card-header {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }

        /* MOBILE */
        @media (max-width: 991.98px) {
            body {
                font-size: 15px;
            }

            .main-header,
            .content-wrapper,
            .main-footer {
                margin-left: 0 !important;
            }

            .main-sidebar {
                transform: translateX(-100%);
                margin-left: 0 !important;
                position: fixed !important;
                top: 58px;
                bottom: 0;
                height: calc(100vh - 58px);
                opacity: 1;
                pointer-events: auto;
                z-index: 1034;
            }

            body.mobile-sidebar-open .main-sidebar {
                transform: translateX(0);
            }

            body.mobile-sidebar-open .mobile-sidebar-overlay {
                display: block;
            }

            .main-header {
                height: 58px;
                position: sticky;
                top: 0;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
                z-index: 1046;
            }

            .content-wrapper {
                min-height: calc(100vh - 58px) !important;
                padding-bottom: calc(var(--mobile-bottom-nav-height) + var(--mobile-utility-height) + var(--mobile-safe-bottom) + 20px);
            }

            body.app-fullscreen .content-wrapper {
                min-height: calc(100vh - 58px) !important;
                padding-bottom: 10px !important;
            }

            .content {
                padding-top: 10px;
            }

            .container-fluid {
                padding-left: 14px !important;
                padding-right: 14px !important;
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

            .navbar-nav .nav-link,
            .dropdown-item {
                min-height: 44px;
                display: flex;
                align-items: center;
            }

            #sidebarToggle {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(226, 232, 240, 0.92);
                box-shadow: 0 10px 22px rgba(15, 23, 42, 0.1);
                z-index: 1048;
            }

            body.mobile-sidebar-open #sidebarToggle {
                position: relative;
                z-index: 1049;
            }

            .topbar-utility {
                margin-right: 6px;
                padding: 0;
                gap: 6px;
            }

            .theme-toggle-btn {
                width: 34px;
                height: 34px;
            }

            .fullscreen-toggle-btn {
                width: 38px;
                height: 38px;
            }

            .content-header {
                margin-bottom: 10px;
            }

            .content-header .row > [class*="col-"] + [class*="col-"] {
                margin-top: 10px;
            }

            .content-header h1 {
                font-size: 1.35rem;
                line-height: 1.2;
            }

            .content-header small {
                display: block;
                font-size: .85rem;
                line-height: 1.5;
            }

            .btn {
                font-weight: 600;
            }

            .modal-dialog {
                margin: 0.75rem;
            }

            .modal-content {
                border-radius: 18px;
                overflow: hidden;
            }

            .modal-body {
                max-height: calc(100vh - 180px);
                overflow-y: auto;
            }

            .table-responsive {
                border-radius: 14px;
            }

            .table-responsive > .table {
                min-width: 680px;
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
                padding-bottom: var(--mobile-safe-bottom);
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

        @media (prefers-reduced-motion: reduce) {
            html, body {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }

            .page-shell > * {
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>
<body class="hold-transition layout-fixed">
<div class="wrapper">

    <div id="mobileSidebarOverlay" class="mobile-sidebar-overlay"></div>

    <nav class="main-header navbar navbar-expand navbar-light shadow-sm">
        <ul class="navbar-nav">
            <li class="nav-item mr-2">
                <a class="nav-link" href="#" id="sidebarToggle" role="button">
                    <span class="burger-icon" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <span class="nav-link font-weight-bold text-dark">POS Mobile Web</span>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item">
                <div class="topbar-utility">
                    <button type="button" id="dock-theme-toggle" class="theme-toggle-btn" title="Ganti tema" aria-label="Ganti tema">
                        <i id="theme-toggle-icon" class="fas fa-moon"></i>
                    </button>
                    <button type="button" id="dock-fullscreen-toggle" class="fullscreen-toggle-btn" title="Mode layar penuh" aria-label="Mode layar penuh">
                        <i id="fullscreen-toggle-icon" class="fas fa-expand"></i>
                    </button>
                </div>
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
                    <a href="<?= base_url('logout') ?>" class="dropdown-item text-danger js-logout-link">
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
                            <p>Kasir</p>
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
                        <a href="<?= base_url('kategori') ?>" class="nav-link <?= $isKategori ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('member') ?>" class="nav-link <?= $isMember ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-id-card-alt"></i>
                            <p>Member</p>
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
                        <a href="<?= base_url('laporan-keuntungan') ?>" class="nav-link <?= $isKeuntungan ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-coins"></i>
                            <p>Laporan Keuntungan</p>
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
                        <a href="<?= base_url('logout') ?>" class="nav-link text-danger js-logout-link">
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
            <div class="container-fluid page-shell" id="pageShell">
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
        const body = $('body');
        const nextOpen = !body.hasClass('desktop-sidebar-open');

        body.toggleClass('desktop-sidebar-open', nextOpen);
        localStorage.setItem('pos_desktop_sidebar_open', nextOpen ? '1' : '0');
    }

    function expandUtilityDock() {
        return;
    }

    function collapseUtilityDock() {
        return;
    }

    function scheduleCollapseUtilityDock() {
        return;
    }

    function showDesktopSidebar() {
        return;
    }

    function hideDesktopSidebarIfNotPinned() {
        return;
    }

    function setupPageReveal() {
        const shell = document.getElementById('pageShell');
        if (!shell || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        const nodes = Array.from(shell.children).filter((node) => {
            return node.nodeType === 1 && !node.classList.contains('modal');
        });

        if (!nodes.length) {
            return;
        }

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, instance) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    instance.unobserve(entry.target);
                });
            }, {
                threshold: 0.08,
                rootMargin: '0px 0px -30px 0px'
            });

            nodes.forEach((node, index) => {
                node.style.transitionDelay = `${Math.min(index * 50, 220)}ms`;
                observer.observe(node);
            });
        } else {
            nodes.forEach((node) => node.classList.add('is-visible'));
        }
    }

    function isAppFullscreenEnabled() {
        return localStorage.getItem('pos_app_fullscreen') === '1';
    }

    function applyAppFullscreenState(enabled) {
        $('body').toggleClass('app-fullscreen', enabled);
    }

    function setFullscreenIcons(isFullscreen) {
        $('#fullscreen-toggle-icon')
            .toggleClass('fa-expand', !isFullscreen)
            .toggleClass('fa-compress', isFullscreen);
    }

    async function toggleFullscreenMode() {
        try {
            const shouldEnable = !isAppFullscreenEnabled();

            localStorage.setItem('pos_app_fullscreen', shouldEnable ? '1' : '0');
            applyAppFullscreenState(shouldEnable);

            if (shouldEnable && !document.fullscreenElement) {
                await document.documentElement.requestFullscreen();
            } else if (!shouldEnable && document.fullscreenElement) {
                await document.exitFullscreen();
            } else {
                setFullscreenIcons(shouldEnable);
            }
        } catch (error) {
            setFullscreenIcons(isAppFullscreenEnabled() || !!document.fullscreenElement);
            Swal.fire('Info', 'Browser di perangkat ini tidak mengizinkan mode layar penuh.', 'info');
        }
    }

    $(document).ready(function() {
        document.body.classList.add('enhanced-ui');
        const savedTheme = localStorage.getItem('pos_theme') || 'light';
        applyTheme(savedTheme);
        applyAppFullscreenState(isAppFullscreenEnabled());
        setFullscreenIcons(isAppFullscreenEnabled() || !!document.fullscreenElement);
        setupPageReveal();
        if (isMobileViewport()) {
            expandUtilityDock();
        } else {
            collapseUtilityDock();
        }

        $('.select2').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                return;
            }

            $(this).select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                allowClear: true
            });
        });

        if (!isMobileViewport()) {
            const savedSidebarOpen = localStorage.getItem('pos_desktop_sidebar_open');
            if (savedSidebarOpen === '1') {
                $('body').addClass('desktop-sidebar-open');
            }
        }

        $('#dock-theme-toggle').on('click', function(e) {
            e.preventDefault();
            const nextTheme = $('body').hasClass('dark-mode') ? 'light' : 'dark';
            localStorage.setItem('pos_theme', nextTheme);
            applyTheme(nextTheme);
            expandUtilityDock();
        });

        $('#dock-fullscreen-toggle').on('click', function(e) {
            e.preventDefault();
            toggleFullscreenMode();
            expandUtilityDock();
        });

        $(document).on('click', '.js-logout-link', function(e) {
            e.preventDefault();

            const logoutUrl = $(this).attr('href');

            Swal.fire({
                title: 'Keluar dari akun?',
                text: 'Sesi Anda akan diakhiri dan Anda perlu login kembali untuk masuk.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#64748b',
                background: $('body').hasClass('dark-mode') ? '#1f2937' : '#ffffff',
                color: $('body').hasClass('dark-mode') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'shadow-lg rounded-lg',
                    confirmButton: 'px-4',
                    cancelButton: 'px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutUrl;
                }
            });
        });

        $('#utilityDockToggle').on('click', function(e) {
            e.preventDefault();
            const dock = $('#utilityDock');
            if (dock.hasClass('is-collapsed')) {
                expandUtilityDock();
            } else {
                collapseUtilityDock();
            }
        });

        $('#sidebarToggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

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
                collapseUtilityDock();
            } else {
                $('body').removeClass('desktop-sidebar-open');
            }
        });

        $(document).on('show.bs.modal', '.modal', function() {
            if (this.parentNode !== document.body) {
                document.body.appendChild(this);
            }
        });

        $('#utilityDock').on('mouseenter', function() {
            if (!isMobileViewport()) {
                expandUtilityDock();
            }
        });

        $('#utilityDock').on('mouseleave', function() {
            if (!isMobileViewport()) {
                collapseUtilityDock();
            }
        });

        $('#utilityDock').on('touchstart', function() {
            return;
        });

        $(document).on('click', function(e) {
            if (isMobileViewport() && $('body').hasClass('mobile-sidebar-open')) {
                if (
                    !$(e.target).closest('.main-sidebar').length &&
                    !$(e.target).closest('#sidebarToggle').length
                ) {
                    closeMobileSidebar();
                    return;
                }
            }

            if (!isMobileViewport() && $('body').hasClass('desktop-sidebar-open')) {
                if (
                    !$(e.target).closest('.main-sidebar').length &&
                    !$(e.target).closest('#sidebarToggle').length
                ) {
                    $('body').removeClass('desktop-sidebar-open');
                    localStorage.setItem('pos_desktop_sidebar_open', '0');
                }
            }

            if ($(e.target).closest('#utilityDock').length) {
                return;
            }

            collapseUtilityDock();
        });

        $(window).on('scroll', function() {
            if (isMobileViewport()) {
                collapseUtilityDock();
            }
        });

        $('.mobile-bottom-nav a, .main-sidebar a').on('click', function() {
            if (isMobileViewport()) {
                collapseUtilityDock();
            }
        });

        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            setFullscreenIcons(isAppFullscreenEnabled() || !!document.fullscreenElement);
        });

    });
</script>

<?= $this->renderSection('script') ?>

</body>
</html>
