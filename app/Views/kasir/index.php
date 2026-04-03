<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
    $session_cart = session()->get('cart') ?? [];
    $grandTotal   = 0;
    $totalItems   = 0;

    foreach ($session_cart as $item) {
        $grandTotal += (float) $item['subtotal'];
        $totalItems += (int) $item['qty'];
    }
?>

<style>
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 0 0 0.5rem 0.5rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        max-height: 320px;
        overflow-y: auto;
        display: none;
    }

    .search-dropdown .dropdown-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f1f1;
        cursor: pointer;
        white-space: normal;
    }

    .search-dropdown .dropdown-item:hover,
    .search-dropdown .dropdown-item.active {
        background-color: #f8f9fa;
        color: #007bff;
    }

    .input-group-lg {
        display: flex !important;
    }

    .input-group-lg > .form-control {
        border-radius: 0 !important;
        height: 54px !important;
    }

    .input-group-lg > .input-group-prepend > .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem !important;
        background: #fff;
    }

    .input-group-lg > .input-group-append > .btn {
        border-radius: 0 0.5rem 0.5rem 0 !important;
    }

    .product-grid {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding-bottom: 10px;
    }

    .product-card {
        flex: 0 0 150px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 12px;
        text-align: left;
        cursor: pointer;
        transition: .2s ease;
    }

    .product-card:hover {
        border-color: #007bff;
        background: #f8f9fa;
        transform: translateY(-2px);
    }

    .product-card .product-name {
        min-height: 38px;
    }

    .summary-chip {
        display: inline-flex;
        align-items: center;
        padding: .45rem .75rem;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #e9ecef;
        margin-right: 8px;
        margin-bottom: 8px;
        font-size: .85rem;
    }

    .summary-chip i {
        margin-right: 8px;
    }

    body.dark-mode .product-card,
    body.dark-mode .summary-chip,
    body.dark-mode .search-dropdown {
        background-color: #2b3035 !important;
        border-color: #495057 !important;
        color: #fff !important;
    }

    body.dark-mode .search-dropdown .dropdown-item {
        border-bottom-color: #495057 !important;
        color: #fff !important;
    }

    body.dark-mode .search-dropdown .dropdown-item:hover {
        background-color: #3c434a !important;
    }

    body.dark-mode .search-dropdown .dropdown-item strong,
    body.dark-mode .search-dropdown .dropdown-item small {
        color: #fff !important;
    }
</style>

<div class="content-header">
    <div class="container-fluid px-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Transaksi Kasir</h1>
                <small class="text-muted">Optimalkan checkout cepat, pencarian produk, dan cetak nota instan.</small>
            </div>
            <div class="col-sm-6 text-sm-right">
                <div class="summary-chip shadow-sm">
                    <i class="fas fa-shopping-basket text-primary"></i>
                    <span id="cart-items-label"><?= $totalItems ?></span> item aktif
                </div>
                <div class="summary-chip shadow-sm">
                    <i class="fas fa-wallet text-success"></i>
                    <span id="cart-total-label">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">

        <div class="mb-3">
            <div class="product-grid">
                <?php foreach (array_slice($products, 0, 8) as $p): ?>
                    <div class="product-card btn-quick-add shadow-sm" data-id="<?= $p['id'] ?>">
                        <small class="text-muted d-block"><?= esc($p['kode_produk'] ?? '-') ?></small>
                        <div class="product-name font-weight-bold"><?= esc($p['nama_produk']) ?></div>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <strong class="text-primary small">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></strong>
                            <span class="badge badge-light">Stok: <?= (int) $p['stok'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body">
                <form id="form-add" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="input-group input-group-lg position-relative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-barcode text-muted"></i></span>
                        </div>

                        <input
                            type="text"
                            id="search-product"
                            class="form-control font-weight-bold"
                            placeholder="Ketik Nama Produk / Scan Barcode..."
                            autofocus
                        >
                        <input type="hidden" name="product_id" id="selected-product-id" required>

                        <input
                            type="number"
                            name="qty"
                            id="input-qty"
                            value="1"
                            class="form-control col-2 text-center"
                            placeholder="Qty"
                            min="1"
                        >

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-submit-cart">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>

                        <div class="search-dropdown" id="search-results"></div>
                    </div>
                </form>

                <div class="table-responsive mt-4" style="height: 420px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px;">
                    <table class="table table-hover table-striped mb-0" id="table-cart">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-3">Produk</th>
                                <th width="120" class="text-center">Harga</th>
                                <th width="80" class="text-center">Qty</th>
                                <th width="140" class="text-right pr-3">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($session_cart)): ?>
                                <?php foreach ($session_cart as $id => $item): ?>
                                    <tr>
                                        <td class="align-middle font-weight-bold pl-3"><?= esc($item['name']) ?></td>
                                        <td class="text-center align-middle">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-info px-2"><?= (int) $item['qty'] ?></span>
                                        </td>
                                        <td class="text-right align-middle font-weight-bold text-primary pr-3">
                                            Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm text-danger btn-remove" data-id="<?= $id ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="empty-row">
                                    <td colspan="5" class="text-center py-5 text-muted small">Keranjang kosong. Tambahkan produk untuk memulai transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box bg-navy shadow-sm mb-3">
            <div class="info-box-content text-right">
                <span class="info-box-text">TOTAL TRANSAKSI</span>
                <span class="info-box-number h1 mb-0" id="label-grandtotal">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <form id="form-checkout">
                    <?= csrf_field() ?>
                    <input type="hidden" name="total" id="hidden-total" value="<?= $grandTotal ?>">
                    <input type="hidden" name="kembalian" id="hidden-kembalian" value="0">

                    <div class="form-group mb-3">
                        <label class="small text-muted font-weight-bold">BAYAR TUNAI (F8)</label>
                        <input
                            type="number"
                            name="bayar"
                            id="input-bayar"
                            class="form-control form-control-lg text-bold text-primary text-right"
                            placeholder="0"
                            required
                            style="font-size: 2rem; height: 80px;"
                        >
                    </div>

                    <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center mb-4 border">
                        <span class="font-weight-bold small text-muted">KEMBALIAN</span>
                        <h3 id="label-kembalian" class="text-success font-weight-bold mb-0">Rp 0</h3>
                    </div>

                    <button
                        type="submit"
                        id="btn-checkout"
                        class="btn btn-success btn-block btn-lg py-3 shadow <?= ($grandTotal > 0) ? '' : 'disabled' ?>"
                        <?= ($grandTotal > 0) ? '' : 'disabled' ?>
                    >
                        <i class="fas fa-cash-register mr-2"></i> SELESAI TRANSAKSI
                    </button>

                    <a
                        href="<?= base_url('kasir/clearCart') ?>"
                        class="btn btn-outline-danger btn-block btn-sm mt-3"
                        onclick="return confirm('Hapus semua isi keranjang?')"
                    >
                        Batalkan Transaksi
                    </a>
                </form>
            </div>
        </div>

        <div class="card card-outline card-secondary shadow-sm mt-3">
            <div class="card-body">
                <div class="small text-muted font-weight-bold mb-2">Shortcut Keyboard</div>
                <div class="d-flex justify-content-between mb-2">
                    <span>F2</span>
                    <span class="text-muted">Fokus ke pencarian produk</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>F8</span>
                    <span class="text-muted">Fokus ke input bayar</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const products = <?= json_encode($products) ?>;
        const searchInput = $('#search-product');
        const searchResults = $('#search-results');
        const productIdInput = $('#selected-product-id');
        const inputQty = $('#input-qty');
        const inputBayar = $('#input-bayar');
        const checkoutButton = $('#btn-checkout');
        const addButton = $('#btn-submit-cart');

        const checkoutButtonDefaultHtml = '<i class="fas fa-cash-register mr-2"></i> SELESAI TRANSAKSI';
        const addButtonDefaultHtml = '<i class="fas fa-cart-plus"></i>';

        let currentTotal = <?= (float) $grandTotal ?>;
        let searchTimer = null;
        let isCheckoutProcessing = false;
        let isAddToCartProcessing = false;

        searchInput.focus();

        function escapeHtml(text) {
            return String(text ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatRupiah(number) {
            return 'Rp ' + (parseFloat(number || 0)).toLocaleString('id-ID');
        }

        function resetInputs() {
            searchInput.val('');
            productIdInput.val('');
            inputQty.val(1);
            searchResults.hide().empty();
            searchInput.focus();
        }

        function calculateItemCount(cart) {
            let count = 0;
            $.each(cart, function(id, item) {
                count += parseInt(item.qty || 0, 10);
            });
            return count;
        }

        function calculateReturn() {
            const bayar = parseFloat(inputBayar.val()) || 0;
            const kembalian = bayar - currentTotal;
            const displayKembalian = kembalian >= 0 ? kembalian : 0;

            $('#label-kembalian').text(formatRupiah(displayKembalian));
            $('#hidden-kembalian').val(displayKembalian);
        }

        function updateTable(cart, total) {
            let html = '';

            currentTotal = parseFloat(total || 0);
            $('#hidden-total').val(currentTotal);
            $('#label-grandtotal').text(formatRupiah(currentTotal));
            $('#cart-total-label').text(formatRupiah(currentTotal));
            $('#cart-items-label').text(calculateItemCount(cart));

            if (cart && Object.keys(cart).length > 0) {
                $.each(cart, function(id, item) {
                    html += `
                        <tr>
                            <td class="align-middle font-weight-bold pl-3">${escapeHtml(item.name)}</td>
                            <td class="text-center align-middle">${formatRupiah(item.price)}</td>
                            <td class="text-center align-middle">
                                <span class="badge badge-info px-2">${parseInt(item.qty || 0, 10)}</span>
                            </td>
                            <td class="text-right align-middle font-weight-bold text-primary pr-3">${formatRupiah(item.subtotal)}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm text-danger btn-remove" data-id="${escapeHtml(id)}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = `
                    <tr class="empty-row">
                        <td colspan="5" class="text-center py-5 text-muted small">
                            Keranjang kosong. Tambahkan produk untuk memulai transaksi.
                        </td>
                    </tr>
                `;
            }

            $('#table-cart tbody').html(html);

            checkoutButton
                .toggleClass('disabled', currentTotal <= 0)
                .prop('disabled', currentTotal <= 0);

            inputBayar.attr('min', currentTotal);
            calculateReturn();
        }

        function renderSearchResults(keyword) {
            searchResults.empty().hide();
            productIdInput.val('');

            if (keyword.length < 1) {
                return;
            }

            const matches = products.filter(function(p) {
                const nama = (p.nama_produk || '').toLowerCase();
                const kode = (p.kode_produk || '').toLowerCase();
                return nama.includes(keyword) || kode.includes(keyword);
            });

            if (matches.length > 0) {
                matches.slice(0, 10).forEach(function(p) {
                    searchResults.append(`
                        <div class="dropdown-item select-item" data-id="${escapeHtml(p.id)}" data-name="${escapeHtml(p.nama_produk)}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${escapeHtml(p.kode_produk || '-')}</strong> - ${escapeHtml(p.nama_produk)}
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-success">Stok: ${parseInt(p.stok || 0, 10)}</span><br>
                                    <small class="text-primary font-weight-bold">${formatRupiah(p.harga_jual)}</small>
                                </div>
                            </div>
                        </div>
                    `);
                });
            } else {
                searchResults.append('<div class="dropdown-item text-muted text-center py-3">Barang tidak ditemukan</div>');
            }

            searchResults.show();
        }

        function addToCart(productId, qty) {
            qty = parseInt(qty, 10) || 1;

            if (!productId) {
                Swal.fire('Info', 'Pilih barang terlebih dahulu.', 'warning');
                return;
            }

            if (qty < 1) {
                Swal.fire('Info', 'Qty minimal 1.', 'warning');
                inputQty.val(1).focus().select();
                return;
            }

            if (isAddToCartProcessing) {
                return;
            }

            isAddToCartProcessing = true;

            $.ajax({
                url: "<?= base_url('kasir/addToCart') ?>",
                type: "POST",
                data: {
                    product_id: productId,
                    qty: qty,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: "JSON",
                beforeSend: function() {
                    addButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        updateTable(response.cart, response.grandTotal);
                        resetInputs();
                    } else {
                        Swal.fire('Gagal', response.message || response.msg || 'Gagal menambahkan barang ke keranjang.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat menambahkan produk ke keranjang.', 'error');
                },
                complete: function() {
                    isAddToCartProcessing = false;
                    addButton.prop('disabled', false).html(addButtonDefaultHtml);
                }
            });
        }

        function resetAfterCheckout() {
            updateTable({}, 0);
            inputBayar.val('');
            $('#hidden-kembalian').val(0);
            $('#label-kembalian').text('Rp 0');
            resetInputs();
        }

        searchInput.on('input', function() {
            clearTimeout(searchTimer);
            const keyword = $(this).val().toLowerCase().trim();

            searchTimer = setTimeout(function() {
                renderSearchResults(keyword);
            }, 150);
        });

        searchInput.on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                const firstItem = searchResults.find('.select-item').first();
                if (firstItem.length) {
                    firstItem.trigger('click');
                }
            }
        });

        $(document).on('click', '.select-item', function() {
            productIdInput.val($(this).data('id'));
            searchInput.val($(this).data('name'));
            searchResults.hide();
            inputQty.focus().select();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#form-add').length) {
                searchResults.hide();
            }
        });

        $('#form-add').on('submit', function(e) {
            e.preventDefault();
            addToCart(productIdInput.val(), inputQty.val());
        });

        $('.btn-quick-add').on('click', function() {
            addToCart($(this).data('id'), 1);
        });

        $(document).on('click', '.btn-remove', function() {
            $.ajax({
                url: "<?= base_url('kasir/remove') ?>/" + $(this).data('id'),
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    updateTable(response.cart || {}, response.total ?? response.grandTotal ?? 0);
                },
                error: function() {
                    Swal.fire('Error', 'Gagal menghapus item dari keranjang.', 'error');
                }
            });
        });

        inputQty.on('input', function() {
            let qty = parseInt($(this).val(), 10) || 1;
            if (qty < 1) {
                qty = 1;
            }
            $(this).val(qty);
        });

        inputBayar.on('input', calculateReturn);

        $('#form-checkout').on('submit', function(e) {
            e.preventDefault();

            if (isCheckoutProcessing) {
                return;
            }

            if (currentTotal <= 0) {
                Swal.fire('Info', 'Keranjang masih kosong.', 'warning');
                return;
            }

            const bayar = parseFloat(inputBayar.val()) || 0;

            if (bayar < currentTotal) {
                Swal.fire('Oops!', 'Uang bayar kurang.', 'error');
                return;
            }

            isCheckoutProcessing = true;

            $.ajax({
                url: "<?= base_url('penjualan/save') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() {
                    checkoutButton
                        .prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Transaksi Berhasil',
                            text: 'Apakah Anda ingin mencetak nota sekarang?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="fas fa-print"></i> Ya, Cetak',
                            cancelButtonText: 'Selesai Tanpa Cetak',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (response.print_url) {
                                    window.open(response.print_url, '_blank', 'width=420,height=650');
                                } else {
                                    Swal.fire('Info', 'URL cetak nota tidak tersedia.', 'info');
                                }
                            }

                            resetAfterCheckout();
                        });
                    } else {
                        Swal.fire('Error', response.msg || response.message || 'Transaksi gagal diproses.', 'error');
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menyimpan transaksi.';

                    if (xhr.responseJSON && (xhr.responseJSON.msg || xhr.responseJSON.message)) {
                        message = xhr.responseJSON.msg || xhr.responseJSON.message;
                    }

                    Swal.fire('Error', message, 'error');
                },
                complete: function() {
                    isCheckoutProcessing = false;
                    checkoutButton
                        .prop('disabled', currentTotal <= 0)
                        .html(checkoutButtonDefaultHtml);
                }
            });
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'F2') {
                e.preventDefault();
                searchInput.focus();
            }

            if (e.key === 'F8') {
                e.preventDefault();
                inputBayar.focus().select();
            }
        });
    });
</script>
<?= $this->endSection() ?>