<?php
/**
 * Script Otomatis: Export DB & Push ke GitHub
 * Lokasi: C:\laragon\www\pos\deploy.php
 */

// --- 1. Konfigurasi ---
$dbName   = 'pos';
$dbUser   = 'root';
$dbPass   = ''; // Kosongkan jika default Laragon
$sqlFile  = 'database_pos.sql';
$commitMsg = "Auto-update: " . date('Y-m-d H:i:s');

// Cari path mysql-bin secara otomatis di Laragon
$mysqlPath = 'C:\laragon\bin\mysql\mysql-*\bin\mysqldump.exe';
$dirs = glob($mysqlPath);
$mysqldump = $dirs[0] ?? 'mysqldump'; // Gunakan yang ditemukan pertama

echo "=== Memulai Proses Backup & Push ===\n";

// --- 2. Proses Export Database ---
echo "[-] Mengekspor database $dbName...\n";
$dumpCommand = sprintf(
    '"%s" -u %s %s %s > %s',
    $mysqldump,
    $dbUser,
    (!empty($dbPass) ? "-p$dbPass" : ""),
    $dbName,
    $sqlFile
);

system($dumpCommand, $result);

if ($result !== 0) {
    die("[!] Gagal mengekspor database. Pastikan MySQL aktif.\n");
}
echo "[✓] Database berhasil diekspor ke $sqlFile\n";

// --- 3. Proses Git ---
echo "[-] Menjalankan Git Add...\n";
system('git add .');

echo "[-] Menjalankan Git Commit...\n";
system("git commit -m \"$commitMsg\"");

echo "[-] Menjalankan Git Push...\n";
system('git push origin main');

echo "=== Semua proses selesai! ===\n";