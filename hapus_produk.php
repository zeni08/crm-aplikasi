<?php
// hapus_produk.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $produk_id = $conn->real_escape_string($_GET['id']);

    // Query untuk menghapus data produk
    $sql = "DELETE FROM produk WHERE produk_id = $produk_id";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman produk.php
        header("Location: produk.php?status=deleted_success");
        exit();
    } else {
        // Jika terjadi error saat penghapusan
        echo "Error menghapus produk: " . $sql . "<br>" . $conn->error;
        // Opsional: Arahkan kembali dengan pesan error
        // header("Location: produk.php?status=deleted_error");
        // exit();
    }
} else {
    // Jika ID tidak disediakan atau tidak valid
    echo "ID Produk tidak valid untuk dihapus.";
    // Opsional: Arahkan kembali
    // header("Location: produk.php?status=no_id");
    // exit();
}

$conn->close();
?>