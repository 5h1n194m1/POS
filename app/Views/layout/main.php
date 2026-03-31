<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'POS SAYA' ?> | Admin</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  
  <style>
    /* Perbaikan warna teks agar kontras */
    .nav-sidebar .nav-link p { color: #ced4da !important; }
    .nav-sidebar .nav-link.active p, .nav-sidebar .nav-link:hover p { color: #fff !important; }
    .main-sidebar { background-color: #1a1d20 !important; }
    
    /* Perbaikan untuk Modal jika menggunakan Bootstrap 4 tapi kode modal versi 5 */
    .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
    
    /* Tambahan Shadow Halus */
    .main-header { border-bottom: 1px solid #dee2e6 !important; }

    /* Penyesuaian agar Select2 tidak 'mendelep' atau salah ukuran di AdminLTE */
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link font-weight-bold text-dark">Sistem Informasi Kasir</span>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('dashboard') ?>" class="brand-link border-bottom-0 text-center">
      <span class="brand-text font-weight-bold">POS SAYA</span>
    </a>

    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-header">MENU UTAMA</li>
          <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('product') ?>" class="nav-link <?= (uri_string() == 'product') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-box"></i>
              <p>Data Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('kasir') ?>" class="nav-link <?= (uri_string() == 'kasir') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Transaksi Kasir</p>
            </a>
          </li>
          <li class="nav-header">SISTEM</li>
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

  <div class="content-wrapper bg-light">
    <section class="content pt-4">
      <div class="container-fluid">
        <?= $this->renderSection('content') ?>
      </div>
    </section>
  </div>

  <footer class="main-footer text-sm text-center">
    <strong>Copyright &copy; 2026 <a href="#">POS SAYA</a>.</strong> All rights reserved.
  </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih --",
            allowClear: true
        });

        // Penyelamat modal (Support data-toggle dan data-bs-toggle)
        $(document).on('click', '[data-toggle="modal"], [data-bs-toggle="modal"]', function(e) {
            e.preventDefault();
            var target = $(this).data('target') || $(this).data('bs-target');
            $(target).modal('show');
        });
    });
</script>

<?= $this->renderSection('script') ?>

</body>
</html>