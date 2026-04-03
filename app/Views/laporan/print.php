<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Print Laporan') ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 14mm 12mm 14mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            margin: 0;
            background: #fff;
            font-size: 12px;
        }

        .report-wrapper {
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .company h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: .5px;
            color: #0f172a;
        }

        .company .subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #475569;
        }

        .report-meta {
            text-align: right;
            font-size: 12px;
            line-height: 1.6;
        }

        .report-title {
            margin-bottom: 14px;
        }

        .report-title h2 {
            margin: 0 0 4px;
            font-size: 20px;
            color: #111827;
        }

        .report-title p {
            margin: 0;
            color: #6b7280;
            font-size: 12px;
        }

        .filter-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .filter-item {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .filter-item .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }

        .filter-item .value {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-top: 4px solid #2563eb;
            border-radius: 8px;
            padding: 12px;
            background: #ffffff;
        }

        .summary-card.green { border-top-color: #16a34a; }
        .summary-card.orange { border-top-color: #ea580c; }
        .summary-card.red { border-top-color: #dc2626; }

        .summary-card .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 6px;
        }

        .summary-card .value {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .section-title {
            margin: 10px 0 8px;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table {
            border: 1px solid #cbd5e1;
        }

        .report-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .3px;
            border: 1px solid #334155;
        }

        .report-table td {
            padding: 9px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
            font-size: 12px;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: #6b7280;
        }

        .footer-wrap {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-note {
            max-width: 55%;
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-box .role {
            font-size: 12px;
            margin-bottom: 54px;
        }

        .signature-box .name {
            font-size: 12px;
            font-weight: 700;
            border-top: 1px solid #111827;
            padding-top: 6px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            color: #64748b;
            background: #f8fafc;
        }

        .print-actions {
            margin-top: 18px;
        }

        .print-actions button {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 8px;
        }

        .btn-print {
            background: #111827;
            color: #fff;
        }

        .btn-close {
            background: #e5e7eb;
            color: #111827;
        }

        @media print {
            .print-actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="report-wrapper">
    <div class="header">
        <div class="company">
            <h1><?= esc($store_name ?? 'POS SAYA') ?></h1>
            <div class="subtitle"><?= esc($store_subtitle ?? 'Sistem Informasi Kasir') ?></div>
        </div>
        <div class="report-meta">
            <div><strong>Dicetak:</strong> <?= esc($printed_at ?? '-') ?></div>
            <div><strong>Oleh:</strong> <?= esc($report_generated_by ?? '-') ?></div>
        </div>
    </div>

    <div class="report-title">
        <h2>Laporan Penjualan</h2>
        <p>Dokumen ringkasan transaksi penjualan untuk keperluan monitoring dan pelaporan.</p>
    </div>

    <div class="filter-box">
        <div class="filter-item">
            <div class="label">Periode Mulai</div>
            <div class="value"><?= date('d M Y', strtotime($start_date)) ?></div>
        </div>
        <div class="filter-item">
            <div class="label">Periode Selesai</div>
            <div class="value"><?= date('d M Y', strtotime($end_date)) ?></div>
        </div>
        <div class="filter-item">
            <div class="label">Kasir</div>
            <div class="value"><?= esc($cashier_name ?? 'Semua Kasir') ?></div>
        </div>
        <div class="filter-item">
            <div class="label">Jumlah Baris</div>
            <div class="value"><?= number_format(count($rows ?? []), 0, ',', '.') ?> transaksi</div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="label">Total Transaksi</div>
            <div class="value"><?= number_format((float) ($summary['total_transaksi'] ?? 0), 0, ',', '.') ?></div>
        </div>

        <div class="summary-card green">
            <div class="label">Omzet</div>
            <div class="value">Rp <?= number_format((float) ($summary['omzet'] ?? 0), 0, ',', '.') ?></div>
        </div>

        <div class="summary-card orange">
            <div class="label">Total Item</div>
            <div class="value"><?= number_format((int) ($total_item ?? 0), 0, ',', '.') ?></div>
        </div>

        <div class="summary-card red">
            <div class="label">Rata-rata Transaksi</div>
            <div class="value">Rp <?= number_format((float) ($summary['rata_transaksi'] ?? 0), 0, ',', '.') ?></div>
        </div>
    </div>

    <div class="section-title">Detail Transaksi</div>

    <?php if (!empty($rows)): ?>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 14%;">Invoice</th>
                    <th style="width: 16%;">Tanggal</th>
                    <th style="width: 18%;">Kasir</th>
                    <th style="width: 10%;">Total Item</th>
                    <th style="width: 14%;">Total Harga</th>
                    <th style="width: 14%;">Bayar</th>
                    <th style="width: 14%;">Kembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td>
                            INV-<?= date('Ymd', strtotime($row['created_at'])) ?>-<?= str_pad((string) $row['id'], 5, '0', STR_PAD_LEFT) ?>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= esc($row['fullname']) ?></td>
                        <td class="text-center"><?= (int) $row['total_item'] ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['bayar'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format((float) $row['kembalian'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            Tidak ada data transaksi pada periode yang dipilih.
        </div>
    <?php endif; ?>

    <div class="footer-wrap">
        <div class="footer-note">
            Laporan ini dihasilkan oleh sistem dan digunakan sebagai dokumen operasional internal.
            Pastikan data transaksi sudah sesuai sebelum dicetak atau dikirimkan untuk keperluan pelaporan.
        </div>

        <div class="signature-box">
            <div class="role">Mengetahui,</div>
            <div class="name"><?= esc($report_generated_by ?? 'Admin') ?></div>
        </div>
    </div>

    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">Print</button>
        <button class="btn-close" onclick="window.close()">Tutup</button>
    </div>
</div>
</body>
</html>