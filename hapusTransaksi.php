<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    header('Location: listTransaksi.php');
    exit();
}

// Hapus data dari database
$query = "DELETE FROM transaksi WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    // Redirect ke halaman utama dengan pesan sukses
    header('Location: listTransaksi.php?pesan=hapus_sukses');
} else {
    // Redirect ke halaman utama dengan pesan error
    header('Location: listTransaksi.php?pesan=hapus_gagal');
}

exit();
?>