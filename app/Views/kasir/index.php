<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form action="<?= base_url('kasir/add') ?>" method="POST" id="form-add">
                            <?= csrf_field() ?>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <select name="product_id" id="scan-barcode" class="form-control" required>
                                    <option value="">Cari Nama Barang atau Scan Barcode...</option>
                                    <?php foreach($products as $p): ?>
                                        <option value="<?= $p['id'] ?>">
                                            <?= $p['kode_produk'] ?> - <?= $p['nama_produk'] ?> (Stok: <?= $p['stok'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <input type="number" name="qty" value="1" class="form-control col-2" placeholder="Qty" min="1">
                                
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-cart-plus mr-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block ml-1">Tips: Tekan <b>F2</b> untuk mencari barang, tekan <b>F8</b> untuk pembayaran.</small>
                        </form>
                    </div>
                </div>

                <div class="table-responsive" style="height: 400px; overflow-y: auto; border: 1px solid #dee2e6;">
                    <table class="table table-sm table-striped table-hover table-bordered mb-0">
                        <thead class="bg-dark text-white text-center">
                            <tr>
                                <th>Produk</th>
                                <th width="140">Harga Satuan</th>
                                <th width="80">Qty</th>
                                <th width="150">Subtotal</th>
                                <th width="50">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grandTotal = 0; 
                            if(!empty($cart)): 
                                foreach($cart as $id => $item): 
                                    $grandTotal += $item['subtotal']; 
                            ?>
                            <tr>
                                <td class="align-middle px-3 text-bold"><?= esc($item['name']) ?></td>
                                <td class="align-middle text-center">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td class="text-center align-middle"><?= $item['qty'] ?></td>
                                <td class="text-right align-middle font-weight-bold text-primary px-3">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                <td class="text-center align-middle">
                                    <a href="<?= base_url('kasir/remove/'.$id) ?>" class="btn btn-xs btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted small italic">
                                    <i class="fas fa-shopping-basket fa-3x mb-3 d-block opacity-50"></i>
                                    Keranjang masih kosong.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box bg-navy elevation-2 mb-3 shadow">
            <div class="info-box-content text-right p-3">
                <span class="info-box-text text-lg opacity-75">TOTAL BELANJA</span>
                <span class="info-box-number display-4" id="label-grandtotal" style="font-weight: 700;">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <form action="<?= base_url('kasir/checkout') ?>" method="POST" id="form-checkout">
                    <?= csrf_field() ?>
                    <div class="form-group mb-4">
                        <label class="text-muted small mb-1">DIBAYAR (F8)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white text-bold border-0">Rp</span>
                            </div>
                            <input type="number" name="bayar" id="input-bayar" class="form-control form-control-lg text-bold text-primary border-primary" 
                                   placeholder="0" min="<?= $grandTotal ?>" required autocomplete="off" 
                                   style="font-size: 1.5rem; height: calc(2.875rem + 10px);">
                        </div>
                    </div>
                    
                    <div class="form-group py-3 px-3 border-top border-bottom bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="text-muted small mb-0 font-weight-bold">KEMBALIAN</label>
                            <h2 id="label-kembalian" class="text-success font-weight-bold mb-0">Rp 0</h2>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block btn-lg shadow-lg mt-4 py-3 <?= ($grandTotal <= 0) ? 'disabled' : '' ?>" <?= ($grandTotal <= 0) ? 'disabled' : '' ?>>
                        <i class="fas fa-print mr-2"></i> SELESAI & CETAK (Enter)
                    </button>
                    
                    <a href="<?= base_url('kasir/clear') ?>" class="btn btn-outline-danger btn-block btn-sm mt-3 border-0" onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        <i class="fas fa-trash-alt mr-1"></i> Batalkan Transaksi
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        const grandTotal = <?= $grandTotal ?>;
        const inputBayar = $('#input-bayar');
        const labelKembalian = $('#label-kembalian');

        // 1. Inisialisasi Select2
        $('#scan-barcode').select2({
            theme: 'bootstrap4',
            placeholder: 'Ketik Nama Produk atau Kode...',
            allowClear: true,
            width: 'resolve'
        });

        // 2. Fokus otomatis ke pencarian saat halaman dimuat
        $('#scan-barcode').select2('open');

        // 3. Fokuskan kursor ke kolom cari di dalam Select2 saat terbuka
        $(document).on('select2:open', () => {
            setTimeout(() => {
                document.querySelector('.select2-search__field').focus();
            }, 10);
        });

        // 4. Hitung kembalian secara Live
        inputBayar.on('input', function() {
            const bayar = parseFloat($(this).val()) || 0;
            const kembalian = bayar - grandTotal;
            
            if (kembalian >= 0) {
                labelKembalian.text('Rp ' + kembalian.toLocaleString('id-ID'));
                labelKembalian.removeClass('text-danger').addClass('text-success');
            } else {
                labelKembalian.text('Rp 0');
                labelKembalian.removeClass('text-success').addClass('text-danger');
            }
        });

        // 5. Keyboard Shortcuts
        $(document).on('keydown', function(e) {
            // F2 untuk fokus/buka pencarian barang
            if (e.key === 'F2') {
                e.preventDefault();
                $('#scan-barcode').select2('open');
            }
            // F8 untuk fokus ke input pembayaran
            if (e.key === 'F8') {
                e.preventDefault();
                inputBayar.focus();
            }
        });

        // 6. Otomatis buka nota di tab baru jika transaksi berhasil
        <?php if(session()->getFlashdata('print_id')): ?>
            const notaUrl = '<?= base_url('kasir/nota/'.session()->getFlashdata('print_id')) ?>';
            window.open(notaUrl, '_blank');
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>