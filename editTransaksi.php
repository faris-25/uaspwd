<?php
include 'koneksi.php';

$error = '';
$success = '';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    header('Location: listTransaksi.php');
    exit();
}

// Ambil data transaksi
$query = "SELECT * FROM transaksi WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: listTransaksi.php');
    exit();
}

$data = mysqli_fetch_assoc($result);

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
        // Update database
        $query = "UPDATE transaksi SET 
                  tanggal = '$tanggal', 
                  jenis = '$jenis', 
                  nominal = '$nominal', 
                  keterangan = '$keterangan' 
                  WHERE id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data transaksi berhasil diupdate!';
            // Redirect setelah 1 detik
            header("refresh:1;url=listTransaksi.php");
        } else {
            $error = 'Gagal mengupdate data: ' . mysqli_error($conn);
        }
    }
} else {
    // Isi form dengan data yang ada
    $tanggal = $data['tanggal'];
    $jenis = $data['jenis'];
    $nominal = $data['nominal'];
    $keterangan = $data['keterangan'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h1 class="title">Edit Data Transaksi</h1>
        
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
                    <td><input type="date" id="tanggal" name="tanggal" value="<?= $tanggal ?>" required></td>
                </tr>
                <tr>
                    <td><label for="jenis">Jenis Transaksi</label></td>
                    <td>
                        <select id="jenis" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="pemasukan" <?= $jenis == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                            <option value="pengeluaran" <?= $jenis == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="nominal">Nominal (Rp)</label></td>
                    <td><input type="number" id="nominal" name="nominal" value="<?= $nominal ?>" min="0" required></td>
                </tr>
                <tr>
                    <td><label for="keterangan">Keterangan</label></td>
                    <td><textarea id="keterangan" name="keterangan" rows="4"><?= htmlspecialchars($keterangan) ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" class="form-actions">
                        <button type="submit" class="btn-simpan">Update Data</button>
                        <a href="listTransaksi.php" class="btn-kembali">Kembali</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>