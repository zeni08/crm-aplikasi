<?php
// hapus_pelanggan.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $pelanggan_id = $conn->real_escape_string($_GET['id']);

    // Query untuk menghapus data pelanggan
    $sql = "DELETE FROM pelanggan WHERE pelanggan_id = $pelanggan_id";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman pelanggan.php
        header("Location: pelanggan.php?status=deleted_success");
        exit(); // Penting: Hentikan eksekusi skrip setelah redirect
    } else {
        // Jika terjadi error saat penghapusan
        echo "Error: " . $sql . "<br>" . $conn->error;
        // Anda bisa mengarahkan kembali ke pelanggan.php dengan pesan error juga
        // header("Location: pelanggan.php?status=deleted_error");
        // exit();
    }
} else {
    // Jika ID tidak disediakan atau tidak valid
    echo "ID Pelanggan tidak valid untuk dihapus.";
    // Arahkan kembali ke halaman pelanggan.php
    // header("Location: pelanggan.php?status=no_id");
    // exit();
}

$conn->close();
?>