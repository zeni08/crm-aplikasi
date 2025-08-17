<?php
// hapus_riwayat_promosi.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $riwayat_id = $conn->real_escape_string($_GET['id']);

    // Query untuk menghapus data riwayat promosi
    $sql = "DELETE FROM riwayat_promosi_pelanggan WHERE riwayat_id = '$riwayat_id'";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman riwayat_promosi.php
        header("Location: riwayat_promosi.php?status=deleted_success");
        exit();
    } else {
        // Jika terjadi error saat penghapusan
        echo "Error menghapus riwayat promosi: " . $sql . "<br>" . $conn->error;
        // Opsional: Arahkan kembali dengan pesan error
        // header("Location: riwayat_promosi.php?status=deleted_error");
        // exit();
    }
} else {
    // Jika ID tidak disediakan atau tidak valid
    echo "ID Riwayat Promosi tidak valid untuk dihapus.";
    // Opsional: Arahkan kembali
    // header("Location: riwayat_promosi.php?status=no_id");
    // exit();
}

$conn->close();
?>