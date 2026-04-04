<?php if (empty($rows)): ?>
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-2x mb-3"></i>
        <div>Belum ada transaksi untuk filter yang dipilih.</div>
    </div>
<?php else: ?>
    <div class="table-responsive d-none d-md-block">
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

    <div class="d-md-none p-3">
        <?php foreach ($rows as $row): ?>
            <?php $invoiceNo = 'INV-' . date('Ymd', strtotime($row['created_at'])) . '-' . str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT); ?>
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="pr-2">
                            <div class="font-weight-bold"><?= esc($invoiceNo) ?></div>
                            <div class="small text-muted"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></div>
                            <div class="small text-muted"><?= esc($row['fullname']) ?></div>
                        </div>
                        <span class="badge badge-info"><?= (int) $row['total_item'] ?> item</span>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Total</small>
                            <strong class="text-primary">Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></strong>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted d-block">Bayar</small>
                            <span>Rp <?= number_format((float) $row['bayar'], 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="mt-2">
                        <small class="text-muted d-block">Kembalian</small>
                        <strong class="text-success">Rp <?= number_format((float) $row['kembalian'], 0, ',', '.') ?></strong>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-detail-transaksi mb-2 w-100" data-id="<?= $row['id'] ?>">
                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                        </button>
                        <div class="row">
                            <div class="col-6 pr-1">
                                <a href="<?= base_url('penjualan/print_nota/' . $row['id'] . '?paper=80') ?>" target="_blank" class="btn btn-sm btn-outline-success w-100">
                                    Cetak 80
                                </a>
                            </div>
                            <div class="col-6 pl-1">
                                <a href="<?= base_url('penjualan/print_nota/' . $row['id'] . '?paper=58') ?>" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                                    Cetak 58
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
