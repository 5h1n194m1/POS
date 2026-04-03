<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota <?= esc($invoice_no ?? 'Transaksi') ?></title>
    <?php
        $paper = ($paper_width ?? '80') === '58' ? '58' : '80';

        if ($paper === '58') {
            $pageSize     = '58mm auto';
            $bodyWidth    = '50mm';
            $fontSize     = '10px';
            $storeFont    = '14px';
            $smallFont    = '9px';
        } else {
            $pageSize     = '80mm auto';
            $bodyWidth    = '72mm';
            $fontSize     = '11px';
            $storeFont    = '16px';
            $smallFont    = '10px';
        }
    ?>
    <style>
        @page {
            size: <?= $pageSize ?>;
            margin: 0;
        }

        body {
            font-family: "Courier New", monospace;
            width: <?= $bodyWidth ?>;
            margin: 0 auto;
            padding: 5mm 3mm;
            color: #000;
            background: #fff;
            font-size: <?= $fontSize ?>;
            line-height: 1.45;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: <?= $smallFont ?>; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .store-name {
            font-size: <?= $storeFont ?>;
            font-weight: bold;
            letter-spacing: .3px;
        }

        .item-name {
            font-weight: bold;
            word-break: break-word;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 0;
            vertical-align: top;
        }

        .totals td {
            padding: 2px 0;
        }

        .thanks {
            margin-top: 10px;
            text-align: center;
            font-size: <?= $smallFont ?>;
        }

        .print-info {
            margin-top: 10px;
            text-align: center;
            font-size: <?= $smallFont ?>;
            color: #444;
        }

        .no-print {
            margin-top: 12px;
            text-align: center;
        }

        .no-print button {
            padding: 6px 10px;
            border: 1px solid #333;
            background: #fff;
            cursor: pointer;
            font-size: 11px;
            margin: 0 4px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                width: <?= $bodyWidth ?>;
                padding: 4mm 2.5mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <div class="store-name"><?= esc($store_name ?? 'POS SAYA') ?></div>
        <div><?= esc($store_info ?? 'Sistem Informasi Kasir') ?></div>

        <?php if (!empty($store_addr)): ?>
            <div class="small mt-1"><?= esc($store_addr) ?></div>
        <?php endif; ?>

        <?php if (!empty($store_phone)): ?>
            <div class="small"><?= esc($store_phone) ?></div>
        <?php endif; ?>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td>No. Nota</td>
            <td class="right"><?= esc($invoice_no ?? '-') ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="right">
                <?= !empty($penjualan['created_at']) ? date('d/m/Y H:i', strtotime($penjualan['created_at'])) : '-' ?>
            </td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="right"><?= esc($penjualan['fullname'] ?? $penjualan['username'] ?? '-') ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <?php if (!empty($details)): ?>
        <?php foreach ($details as $item): ?>
            <div class="item-name"><?= esc($item['nama_produk']) ?></div>
            <table>
                <tr>
                    <td>
                        <?= (int) $item['qty'] ?> x Rp <?= number_format((float) $item['harga_jual'], 0, ',', '.') ?>
                    </td>
                    <td class="right">
                        Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?>
                    </td>
                </tr>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td class="bold">TOTAL</td>
            <td class="right bold">Rp <?= number_format((float) ($penjualan['total_harga'] ?? 0), 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">Rp <?= number_format((float) ($penjualan['bayar'] ?? 0), 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">Rp <?= number_format((float) ($penjualan['kembalian'] ?? 0), 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="thanks">
        Terima kasih telah berbelanja<br>
        Barang yang sudah dibeli tidak dapat ditukar
    </div>

    <div class="print-info">
        Mode Printer: <?= esc($paper) ?>mm
    </div>

    <div class="no-print">
        <button onclick="window.print()">Print Ulang</button>
        <button onclick="window.close()">Tutup</button>
    </div>

</body>
</html>