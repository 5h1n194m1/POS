<div class="table-responsive d-none d-md-block">
    <table class="table table-hover table-striped mb-0">
        <thead class="thead-light">
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Member</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Modal</th>
                <th class="text-right">Total Jual</th>
                <th class="text-right">Laba</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td class="font-weight-bold">
                            INV-<?= date('Ymd', strtotime($row['created_at'])) ?>-<?= str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT) ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['fullname'] ?? '-') ?></td>
                        <td><?= esc($row['member_nama'] ?: '-') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['subtotal_kotor'], 0, ',', '.') ?></td>
                        <td class="text-right text-danger">Rp <?= number_format((float) $row['diskon_nominal'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['total_modal'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></td>
                        <td class="text-right font-weight-bold text-success">Rp <?= number_format((float) $row['laba_bersih'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data keuntungan pada filter ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-md-none p-3">
    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $row): ?>
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="font-weight-bold">
                                INV-<?= date('Ymd', strtotime($row['created_at'])) ?>-<?= str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT) ?>
                            </div>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></small>
                        </div>
                        <span class="badge badge-success px-3 py-2">
                            Rp <?= number_format((float) $row['laba_bersih'], 0, ',', '.') ?>
                        </span>
                    </div>
                    <div class="small text-muted mt-2">Kasir: <?= esc($row['fullname'] ?? '-') ?></div>
                    <div class="small text-muted">Member: <?= esc($row['member_nama'] ?: '-') ?></div>
                    <div class="row mt-3">
                        <div class="col-6 small">Subtotal<br><strong>Rp <?= number_format((float) $row['subtotal_kotor'], 0, ',', '.') ?></strong></div>
                        <div class="col-6 small">Diskon<br><strong class="text-danger">Rp <?= number_format((float) $row['diskon_nominal'], 0, ',', '.') ?></strong></div>
                        <div class="col-6 small mt-2">Modal<br><strong>Rp <?= number_format((float) $row['total_modal'], 0, ',', '.') ?></strong></div>
                        <div class="col-6 small mt-2">Total Jual<br><strong>Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></strong></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-4 text-muted">Belum ada data keuntungan pada filter ini.</div>
    <?php endif; ?>
</div>
