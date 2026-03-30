<!DOCTYPE html>
<html>
<head>
    <style>
        @media print { @page { margin: 0; } body { margin: 0.5cm; } }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; width: 58mm; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        hr { border-top: 1px dashed #000; }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">
    <div class="text-center">
        <strong>POS SAYA</strong><br>
        Jl. Kaliangkrik, Magelang<br>
        Telp: 08123456789
    </div>
    <hr>
    <div>
        No: #<?= str_pad($penjualan['id'], 5, '0', STR_PAD_LEFT) ?><br>
        Tgl: <?= date('d/m/Y H:i', strtotime($penjualan['created_at'])) ?>
    </div>
    <hr>
    <table>
        <?php foreach($details as $d): ?>
        <tr>
            <td colspan="2"><?= $d['nama_produk'] ?></td>
        </tr>
        <tr>
            <td><?= $d['qty'] ?> x <?= number_format($d['subtotal']/$d['qty']) ?></td>
            <td class="text-right"><?= number_format($d['subtotal']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <hr>
    <div class="text-right">
        Total: <?= number_format($penjualan['total_harga']) ?><br>
        Bayar: <?= number_format($penjualan['bayar']) ?><br>
        <strong>Kembali: <?= number_format($penjualan['kembalian']) ?></strong>
    </div>
    <hr>
    <div class="text-center">Terima Kasih Atas Kunjungan Anda</div>
</body>
</html>