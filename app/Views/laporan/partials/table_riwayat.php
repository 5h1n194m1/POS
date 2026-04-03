<?php if (empty($rows)): ?>
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-2x mb-3"></i>
        <div>Belum ada transaksi untuk filter yang dipilih.</div>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th class="text-center">Item</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Bayar</th>
                    <th class="text-right">Kembalian</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <?php $invoiceNo = 'INV-' . date('Ymd', strtotime($row['created_at'])) . '-' . str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT); ?>
                    <tr>
                        <td class="font-weight-bold"><?= esc($invoiceNo) ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['fullname']) ?></td>
                        <td class="text-center">
                            <span class="badge badge-info"><?= (int) $row['total_item'] ?> item</span>
                        </td>
                        <td class="text-right font-weight-bold text-primary">
                            Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?>
                        </td>
                        <td class="text-right">
                            Rp <?= number_format((float) $row['bayar'], 0, ',', '.') ?>
                        </td>
                        <td class="text-right text-success font-weight-bold">
                            Rp <?= number_format((float) $row['kembalian'], 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-detail-transaksi mb-1" data-id="<?= $row['id'] ?>" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>

                            <a href="<?= base_url('penjualan/print_nota/' . $row['id'] . '?paper=80') ?>" target="_blank" class="btn btn-sm btn-outline-success mb-1" title="Print 80mm">
                                80
                            </a>

                            <a href="<?= base_url('penjualan/print_nota/' . $row['id'] . '?paper=58') ?>" target="_blank" class="btn btn-sm btn-outline-secondary mb-1" title="Print 58mm">
                                58
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>