<?php
include 'koneksi.php';

// Hitung total pemasukan
$queryPemasukan = "SELECT SUM(nominal) as total FROM transaksi WHERE jenis = 'pemasukan'";
$resultPemasukan = mysqli_query($conn, $queryPemasukan);
$dataPemasukan = mysqli_fetch_assoc($resultPemasukan);
$totalPemasukan = $dataPemasukan['total'] ?? 0;

// Hitung total pengeluaran
$queryPengeluaran = "SELECT SUM(nominal) as total FROM transaksi WHERE jenis = 'pengeluaran'";
$resultPengeluaran = mysqli_query($conn, $queryPengeluaran);
$dataPengeluaran = mysqli_fetch_assoc($resultPengeluaran);
$totalPengeluaran = $dataPengeluaran['total'] ?? 0;

// Hitung saldo
$saldo = $totalPemasukan - $totalPengeluaran;

// Ambil semua transaksi
$query = "SELECT * FROM transaksi ORDER BY tanggal DESC, id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi Keuangan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h1 class="title">Daftar Transaksi Keuangan</h1>
        
        <div class="action-link">
            <a href="tambahTransaksi.php">Tambah Data Baru</a>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <strong>Total Pemasukan:</strong>
                <div class="text-success">
                    <?= formatRupiah($totalPemasukan) ?>
                </div>
            </div>
            <div class="summary-item">
                <strong>Total Pengeluaran:</strong>
                <div class="text-danger">
                    <?= formatRupiah($totalPengeluaran) ?>
                </div>
            </div>
            <div class="summary-item">
                <strong>Saldo:</strong>
                <div class="<?= $saldo < 0 ? 'text-danger' : 'text-success' ?>">
                    <?= formatRupiah($saldo) ?>
                </div>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>JENIS</th>
                    <th>NOMINAL</th>
                    <th>KETERANGAN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td>
                        <span class="badge-<?= $row['jenis'] ?>">
                            <?= ucfirst($row['jenis']) ?>
                        </span>
                    </td>
                    <td class="nominal-<?= $row['jenis'] ?>">
                        <?= formatRupiah($row['nominal']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td class="action-cell">
                        <a href="editTransaksi.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="hapusTransaksi.php?id=<?= $row['id'] ?>" 
                           class="btn-hapus"
                           onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-message">
            <p>Belum ada data transaksi.</p>
            <a href="tambahTransaksi.php">Tambah transaksi pertama</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>