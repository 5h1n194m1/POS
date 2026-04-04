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
    .kasir-layout {
        align-items: flex-start;
    }

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
        scrollbar-width: thin;
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

    .checkout-breakdown {
        display: grid;
        gap: 10px;
    }

    .checkout-breakdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(248, 250, 252, 0.9);
        border: 1px solid #e5e7eb;
    }

    .checkout-breakdown-item strong {
        font-size: 1rem;
    }

        .cart-table-wrap {
            height: 420px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #eee;
            border-radius: 8px;
        }

    .checkout-card {
        position: sticky;
        top: 78px;
    }

    .shortcut-card {
        position: sticky;
        top: 390px;
    }

    body.dark-mode .product-card,
    body.dark-mode .summary-chip,
    body.dark-mode .search-dropdown,
    body.dark-mode .checkout-breakdown-item {
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

    @media (max-width: 991.98px) {
        .product-grid {
            gap: 10px;
            padding-bottom: 4px;
            margin-left: -2px;
            margin-right: -2px;
        }

        .product-card {
            flex-basis: 132px;
            min-height: 124px;
            border-radius: 14px;
            padding: 12px;
        }

        .product-card .product-name {
            min-height: 48px;
            font-size: .95rem;
            line-height: 1.35;
        }

        .summary-chip {
            width: 100%;
            justify-content: center;
            margin-right: 0;
        }

        .input-group-lg {
            flex-wrap: wrap !important;
        }

        .input-group-lg > .input-group-prepend,
        .input-group-lg > .input-group-append {
            display: flex;
        }

        .input-group-lg > .input-group-prepend > .input-group-text {
            border-radius: 0.75rem 0 0 0.75rem !important;
            height: 54px;
        }

        .input-group-lg > .form-control.font-weight-bold {
            width: calc(100% - 52px);
            flex: 1 1 calc(100% - 52px);
            border-radius: 0 0.75rem 0.75rem 0 !important;
        }

        #input-qty {
            flex: 1 1 100%;
            max-width: 100%;
            margin-top: 10px;
            border-radius: 0.75rem !important;
        }

        .input-group-lg > .input-group-append {
            flex: 1 1 100%;
            margin-top: 10px;
        }

        .input-group-lg > .input-group-append > .btn {
            width: 100%;
            border-radius: 0.75rem !important;
            min-height: 52px;
        }

        .search-dropdown {
            top: calc(100% + 64px);
            border-radius: 0.75rem;
        }

        .cart-table-wrap {
            height: auto;
            max-height: none;
            border: 0;
            margin-top: 18px;
            overflow: visible;
        }

        .cart-table-wrap > .table {
            min-width: 100% !important;
            width: 100% !important;
            table-layout: fixed;
        }

        #table-cart thead {
            display: none;
        }

        #table-cart,
        #table-cart tbody,
        #table-cart tr,
        #table-cart td {
            display: block;
            width: 100%;
        }

        #table-cart tr {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 12px;
            margin-bottom: 12px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        #table-cart tr.empty-row {
            display: block;
            text-align: center;
            padding: 24px 14px;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        #table-cart tr.empty-row td {
            display: block;
            width: 100%;
            max-width: 100%;
            text-align: center !important;
            padding: 0 !important;
            color: #6b7280;
            white-space: normal;
            word-break: break-word;
        }

        #table-cart tr.empty-row td::before {
            content: none !important;
        }

        #table-cart td {
            border: 0 !important;
            padding: 3px 0 !important;
            text-align: left !important;
        }

        #table-cart td:nth-child(2)::before,
        #table-cart td:nth-child(3)::before,
        #table-cart td:nth-child(4)::before {
            display: block;
            font-size: .72rem;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .3px;
            margin-bottom: 2px;
        }

        #table-cart td:nth-child(2)::before {
            content: 'Harga';
        }

        #table-cart td:nth-child(3)::before {
            content: 'Qty';
        }

        #table-cart td:nth-child(4)::before {
            content: 'Subtotal';
        }

        #table-cart td:last-child {
            text-align: right !important;
            padding-top: 8px !important;
        }

        .checkout-card,
        .shortcut-card {
            position: static;
            top: auto;
        }

        .info-box .info-box-number {
            font-size: 1.5rem !important;
            line-height: 1.25;
            word-break: break-word;
        }

        #input-bayar {
            font-size: 1.55rem !important;
            height: 70px !important;
        }
    }

    @media (max-width: 575.98px) {
        .summary-chip {
            font-size: .8rem;
        }
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

<div class="row kasir-layout">
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

                <div class="table-responsive mt-4 cart-table-wrap">
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
                <div class="small mt-2 opacity-75">
                    <div>Subtotal: <span id="label-subtotal">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span></div>
                    <div>Diskon: <span id="label-diskon">Rp 0</span></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-primary checkout-card">
            <div class="card-body">
                <form id="form-checkout">
                    <?= csrf_field() ?>
                    <input type="hidden" name="total" id="hidden-total" value="<?= $grandTotal ?>">
                    <input type="hidden" name="kembalian" id="hidden-kembalian" value="0">

                    <div class="form-group">
                        <label class="small text-muted font-weight-bold">MEMBER SPESIAL</label>
                        <select name="member_id" id="select-member" class="form-control select2" data-placeholder="Pilih member (opsional)">
                            <option value=""></option>
                            <?php foreach (($members ?? []) as $member): ?>
                                <option value="<?= $member['id'] ?>">
                                    <?= esc($member['nama']) ?> | <?= esc($member['no_member']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1">Diskon fleksibel hanya aktif jika member dipilih.</small>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold">TIPE DISKON</label>
                                <select name="diskon_type" id="discount-type" class="form-control" disabled>
                                    <option value="">Tanpa Diskon</option>
                                    <option value="nominal">Nominal</option>
                                    <option value="percent">Persen (%)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold">NILAI DISKON</label>
                                <input
                                    type="number"
                                    name="diskon_input"
                                    id="discount-input"
                                    class="form-control text-right"
                                    value="0"
                                    min="0"
                                    step="0.01"
                                    placeholder="0"
                                    disabled
                                >
                            </div>
                        </div>
                    </div>

                    <div class="checkout-breakdown mb-3">
                        <div class="checkout-breakdown-item">
                            <span class="small font-weight-bold text-muted">Subtotal Kotor</span>
                            <strong id="summary-subtotal">Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong>
                        </div>
                        <div class="checkout-breakdown-item">
                            <span class="small font-weight-bold text-muted">Diskon Member</span>
                            <strong class="text-danger" id="summary-discount">Rp 0</strong>
                        </div>
                    </div>

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

        <div class="card card-outline card-secondary shadow-sm mt-3 shortcut-card">
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
        const memberSelect = $('#select-member');
        const discountType = $('#discount-type');
        const discountInput = $('#discount-input');

        const checkoutButtonDefaultHtml = '<i class="fas fa-cash-register mr-2"></i> SELESAI TRANSAKSI';
        const addButtonDefaultHtml = '<i class="fas fa-cart-plus"></i>';

        let currentGrossTotal = <?= (float) $grandTotal ?>;
        let currentDiscountAmount = 0;
        let currentTotal = <?= (float) $grandTotal ?>;
        let searchTimer = null;
        let isCheckoutProcessing = false;
        let isAddToCartProcessing = false;

        memberSelect.select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: memberSelect.data('placeholder')
        });

        searchInput.focus();
        updateDiscountControls();

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

        function updateDiscountControls() {
            const hasMember = memberSelect.val() !== '';
            discountType.prop('disabled', !hasMember);
            discountInput.prop('disabled', !hasMember);

            if (!hasMember) {
                discountType.val('');
                discountInput.val(0);
            }

            recalculateCheckoutSummary();
        }

        function calculateDiscountAmount() {
            const hasMember = memberSelect.val() !== '';
            const type = discountType.val();
            const rawValue = parseFloat(discountInput.val()) || 0;

            if (!hasMember || !type || rawValue <= 0 || currentGrossTotal <= 0) {
                return 0;
            }

            if (type === 'percent') {
                const percent = Math.min(rawValue, 100);
                return currentGrossTotal * (percent / 100);
            }

            return Math.min(rawValue, currentGrossTotal);
        }

        function recalculateCheckoutSummary() {
            currentDiscountAmount = calculateDiscountAmount();
            currentTotal = Math.max(currentGrossTotal - currentDiscountAmount, 0);

            $('#hidden-total').val(currentTotal);
            $('#label-grandtotal').text(formatRupiah(currentTotal));
            $('#cart-total-label').text(formatRupiah(currentTotal));
            $('#label-subtotal').text(formatRupiah(currentGrossTotal));
            $('#label-diskon').text(formatRupiah(currentDiscountAmount));
            $('#summary-subtotal').text(formatRupiah(currentGrossTotal));
            $('#summary-discount').text(formatRupiah(currentDiscountAmount));

            checkoutButton
                .toggleClass('disabled', currentGrossTotal <= 0)
                .prop('disabled', currentGrossTotal <= 0);

            inputBayar.attr('min', currentTotal);
            calculateReturn();
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

            currentGrossTotal = parseFloat(total || 0);
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
            recalculateCheckoutSummary();
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
            memberSelect.val('').trigger('change');
            discountType.val('');
            discountInput.val(0);
            updateDiscountControls();
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

        memberSelect.on('change', updateDiscountControls);

        discountType.on('change', function() {
            if ($(this).val() !== 'percent' && (parseFloat(discountInput.val()) || 0) < 0) {
                discountInput.val(0);
            }
            recalculateCheckoutSummary();
        });

        discountInput.on('input', function() {
            let value = parseFloat($(this).val()) || 0;
            if (value < 0) {
                value = 0;
            }

            if (discountType.val() === 'percent' && value > 100) {
                value = 100;
            }

            $(this).val(value);
            recalculateCheckoutSummary();
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
                                const printUrl = response.print_url_80 || response.print_url || response.print_url_58 || '';
                                if (printUrl) {
                                    window.open(printUrl, '_blank', 'width=420,height=650');
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
