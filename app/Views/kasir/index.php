<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php 
    // Ambil data keranjang dari session agar saat refresh tetap muncul
    $session_cart = session()->get('cart') ?? [];
    $grandTotal = 0;
?>

<style>
    /* Styling untuk Dropdown Hasil Pencarian Custom */
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 0 0 0.5rem 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        max-height: 300px;
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
    
    /* Input Group Fix */
    .input-group-lg { display: flex !important; }
    .input-group-lg > .form-control { border-radius: 0 !important; height: 50px !important; }
    .input-group-lg > .input-group-prepend > .input-group-text { border-radius: 0.5rem 0 0 0.5rem !important; background: #fff; }
    .input-group-lg > .input-group-append > .btn { border-radius: 0 0.5rem 0.5rem 0 !important; }

    /* GRID PRODUK PREVIEW */
    .product-grid { display: flex; overflow-x: auto; gap: 12px; padding-bottom: 15px; }
    .product-card { flex: 0 0 130px; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 10px; text-align: center; cursor: pointer; transition: 0.2s; }
    .product-card:hover { border-color: #007bff; background: #f8f9fa; transform: translateY(-2px); }
</style>

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <div class="product-grid">
                <?php foreach(array_slice($products, 0, 6) as $p): ?>
                <div class="product-card btn-quick-add" data-id="<?= $p['id'] ?>">
                    <small class="d-block text-truncate font-weight-bold"><?= $p['nama_produk'] ?></small>
                    <strong class="text-primary small">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></strong>
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
                        
                        <input type="text" id="search-product" class="form-control font-weight-bold" placeholder="Ketik Nama Produk / Scan Barcode..." autofocus>
                        <input type="hidden" name="product_id" id="selected-product-id" required>
                        <input type="number" name="qty" id="input-qty" value="1" class="form-control col-2 text-center" placeholder="Qty" min="1">
                        
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-submit-cart">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                        <div class="search-dropdown" id="search-results"></div>
                    </div>
                </form>

                <div class="table-responsive mt-4" style="height: 380px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px;">
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
                            <?php if(!empty($session_cart)): ?>
                                <?php foreach($session_cart as $id => $item): 
                                    $grandTotal += $item['subtotal'];
                                ?>
                                <tr>
                                    <td class="align-middle font-weight-bold pl-3"><?= esc($item['name']) ?></td>
                                    <td class="text-center align-middle">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td class="text-center align-middle"><span class="badge badge-info px-2"><?= $item['qty'] ?></span></td>
                                    <td class="text-right align-middle font-weight-bold text-primary pr-3">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm text-danger btn-remove" data-id="<?= $id ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="empty-row"><td colspan="5" class="text-center py-5 text-muted small">Keranjang Kosong</td></tr>
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
                <form action="<?= base_url('kasir/checkout') ?>" method="POST" id="form-checkout">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label class="small text-muted font-weight-bold">BAYAR TUNAI (F8)</label>
                        <input type="number" name="bayar" id="input-bayar" class="form-control form-control-lg text-bold text-primary text-right" placeholder="0" required style="font-size: 2rem; height: 80px;">
                    </div>
                    
                    <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center mb-4 border">
                        <span class="font-weight-bold small text-muted">KEMBALIAN</span>
                        <h3 id="label-kembalian" class="text-success font-weight-bold mb-0">Rp 0</h3>
                    </div>

                    <button type="submit" id="btn-checkout" class="btn btn-success btn-block btn-lg py-3 shadow <?= ($grandTotal > 0) ? '' : 'disabled' ?>" <?= ($grandTotal > 0) ? '' : 'disabled' ?>>
                        <i class="fas fa-print mr-2"></i> SELESAI & CETAK
                    </button>
                    <a href="<?= base_url('kasir/clearCart') ?>" class="btn btn-outline-danger btn-block btn-sm mt-3" onclick="return confirm('Hapus semua isi keranjang?')">Batalkan Transaksi</a>
                </form>
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
        
        // PENTING: Inisialisasi total dari PHP agar sinkron saat refresh
        let currentTotal = <?= $grandTotal ?>;

        searchInput.focus();

        // 1. Live Search
        searchInput.on('input', function() {
            const keyword = $(this).val().toLowerCase();
            searchResults.empty().hide();
            productIdInput.val(''); 

            if (keyword.length < 1) return;

            let matches = products.filter(p => 
                p.nama_produk.toLowerCase().includes(keyword) || 
                p.kode_produk.toLowerCase().includes(keyword)
            );

            if (matches.length > 0) {
                matches.slice(0, 10).forEach(p => {
                    searchResults.append(`
                        <div class="dropdown-item select-item" data-id="${p.id}" data-name="${p.nama_produk}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><strong class="text-dark">${p.kode_produk}</strong> - ${p.nama_produk}</div>
                                <div class="text-right">
                                    <span class="badge badge-success">Stok: ${p.stok}</span><br>
                                    <small class="text-primary font-weight-bold">Rp ${parseInt(p.harga_jual).toLocaleString('id-ID')}</small>
                                </div>
                            </div>
                        </div>
                    `);
                });
                searchResults.show();
            } else {
                searchResults.append('<div class="dropdown-item text-muted text-center py-3">Barang tidak ditemukan</div>');
                searchResults.show();
            }
        });

        $(document).on('click', '.select-item', function() {
            productIdInput.val($(this).data('id'));
            searchInput.val($(this).data('name'));
            searchResults.hide();
            inputQty.focus().select(); 
        });

        // 2. Barcode & Enter Logic
        searchInput.on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                const keyword = $(this).val().toLowerCase();
                const exactMatch = products.find(p => p.kode_produk.toLowerCase() === keyword);
                
                if (exactMatch) {
                    productIdInput.val(exactMatch.id);
                    addToCart(exactMatch.id, inputQty.val());
                } else if (productIdInput.val() !== '') {
                    inputQty.focus().select();
                }
            }
        });

        // 3. Ajax Keranjang
        function addToCart(productId, qty) {
            if(!productId) {
                Swal.fire('Info', 'Pilih barang terlebih dahulu!', 'warning');
                return;
            }

            $.ajax({
                url: "<?= base_url('kasir/addToCart') ?>",
                type: "POST",
                data: { product_id: productId, qty: qty, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: "JSON",
                success: function(response) {
                    if (response.status === 'success') {
                        updateTable(response.cart, response.grandTotal);
                        resetInputs();
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                }
            });
        }

        function resetInputs() {
            searchInput.val('');
            productIdInput.val('');
            inputQty.val(1);
            searchResults.hide();
            searchInput.focus();
        }

        $('#form-add').on('submit', function(e) {
            e.preventDefault();
            addToCart(productIdInput.val(), inputQty.val());
        });

        $('.btn-quick-add').on('click', function() {
            addToCart($(this).data('id'), 1);
        });

        // 4. Update UI
        function updateTable(cart, total) {
            let html = '';
            currentTotal = total;
            if (Object.keys(cart).length > 0) {
                $.each(cart, function(id, item) {
                    html += `<tr>
                        <td class="align-middle font-weight-bold pl-3">${item.name}</td>
                        <td class="text-center align-middle">Rp ${parseInt(item.price).toLocaleString('id-ID')}</td>
                        <td class="text-center align-middle"><span class="badge badge-info px-2">${item.qty}</span></td>
                        <td class="text-right align-middle font-weight-bold text-primary pr-3">Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
                        <td class="text-center"><button type="button" class="btn btn-sm text-danger btn-remove" data-id="${id}"><i class="fas fa-trash"></i></button></td>
                    </tr>`;
                });
            } else {
                html = '<tr class="empty-row"><td colspan="5" class="text-center py-5 text-muted small">Keranjang Kosong</td></tr>';
            }
            $('#table-cart tbody').html(html);
            $('#label-grandtotal').text('Rp ' + total.toLocaleString('id-ID'));
            inputBayar.attr('min', total);
            $('#btn-checkout').toggleClass('disabled', total <= 0).prop('disabled', total <= 0);
            calculateReturn();
        }

        $(document).on('click', '.btn-remove', function() {
            $.ajax({
                url: "<?= base_url('kasir/remove') ?>/" + $(this).data('id'),
                type: "GET", dataType: "JSON",
                success: function(res) { updateTable(res.cart, res.total); }
            });
        });

        function calculateReturn() {
            const bayar = parseFloat(inputBayar.val()) || 0;
            const kembalian = bayar - currentTotal;
            $('#label-kembalian').text('Rp ' + (kembalian >= 0 ? kembalian.toLocaleString('id-ID') : 0));
        }
        
        inputBayar.on('input', calculateReturn);

        $(document).on('keydown', function(e) {
            if (e.key === 'F2') { e.preventDefault(); searchInput.focus(); }
            if (e.key === 'F8') { e.preventDefault(); inputBayar.focus().select(); }
        });
    });
</script>
<?= $this->endSection() ?>