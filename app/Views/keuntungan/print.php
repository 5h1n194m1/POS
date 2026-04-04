<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Keuntungan') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 24px;
        }

        h1, h2, p {
            margin: 0;
        }

        .header {
            margin-bottom: 18px;
        }

        .meta {
            margin-top: 10px;
            line-height: 1.6;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
        }

        .summary td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th,
        table.report td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            vertical-align: top;
        }

        table.report th {
            background: #f3f4f6;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-success {
            color: #047857;
            font-weight: bold;
        }

        .footer {
            margin-top: 16px;
            color: #6b7280;
            font-size: 11px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1><?= esc($store_name ?? 'POS SAYA') ?></h1>
        <p><?= esc($store_subtitle ?? 'Sistem Informasi Kasir') ?></p>
        <div class="meta">
            <div>Periode: <?= esc($start_date) ?> s/d <?= esc($end_date) ?></div>
            <div>Kasir: <?= esc($cashier_name ?? 'Semua Kasir') ?></div>
            <div>Dicetak: <?= esc($printed_at ?? date('d M Y H:i:s')) ?></div>
            <div>Oleh: <?= esc($report_generated_by ?? '-') ?></div>
        </div>
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Transaksi</strong><br><?= number_format((int) ($summary['total_transaksi'] ?? 0), 0, ',', '.') ?></td>
            <td><strong>Omzet Kotor</strong><br>Rp <?= number_format((float) ($summary['omzet_kotor'] ?? 0), 0, ',', '.') ?></td>
            <td><strong>Total Diskon</strong><br>Rp <?= number_format((float) ($summary['total_diskon'] ?? 0), 0, ',', '.') ?></td>
            <td><strong>Total Modal</strong><br>Rp <?= number_format((float) ($summary['total_modal'] ?? 0), 0, ',', '.') ?></td>
            <td><strong>Laba Bersih</strong><br>Rp <?= number_format((float) ($summary['laba_bersih'] ?? 0), 0, ',', '.') ?></td>
        </tr>
    </table>

    <table class="report">
        <thead>
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
                        <td>INV-<?= date('Ymd', strtotime($row['created_at'])) ?>-<?= str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['fullname'] ?? '-') ?></td>
                        <td><?= esc($row['member_nama'] ?: '-') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['subtotal_kotor'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['diskon_nominal'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['total_modal'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></td>
                        <td class="text-right text-success">Rp <?= number_format((float) $row['laba_bersih'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align:center; padding: 20px;">Tidak ada data keuntungan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Laporan keuntungan ini dibuat otomatis dari transaksi yang tersimpan di sistem.
    </div>
</body>
</html>
