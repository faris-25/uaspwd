<?php
include 'koneksi.php';

$error = '';
$success = '';

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $nominal = $_POST['nominal'];
    $keterangan = $_POST['keterangan'];
    
    // Validasi input
    if (empty($tanggal) || empty($jenis) || empty($nominal)) {
        $error = 'Tanggal, Jenis, dan Nominal wajib diisi!';
    } else {
        // Insert ke database
        $query = "INSERT INTO transaksi (tanggal, jenis, nominal, keterangan) 
                  VALUES ('$tanggal', '$jenis', '$nominal', '$keterangan')";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data transaksi berhasil ditambahkan!';
            // Redirect setelah 1 detik
            header("refresh:1;url=listTransaksi.php");
        } else {
            $error = 'Gagal menambahkan data: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h1 class="title">Tambah Data Transaksi</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="form-style">
            <table class="form-table">
                <tr>
                    <td><label for="tanggal">Tanggal</label></td>
                    <td><input type="date" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required></td>
                </tr>
                <tr>
                    <td><label for="jenis">Jenis Transaksi</label></td>
                    <td>
                        <select id="jenis" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="nominal">Nominal (Rp)</label></td>
                    <td><input type="number" id="nominal" name="nominal" placeholder="Contoh: 50000" min="0" required></td>
                </tr>
                <tr>
                    <td><label for="keterangan">Keterangan</label></td>
                    <td><textarea id="keterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan (opsional)"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" class="form-actions">
                        <button type="submit" class="btn-simpan">Simpan Data</button>
                        <a href="listTransaksi.php" class="btn-kembali">Kembali</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>