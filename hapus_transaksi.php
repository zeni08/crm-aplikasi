<?php
// hapus_transaksi.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $transaksi_id = $conn->real_escape_string($_GET['id']);

    // Mulai transaksi database untuk memastikan kedua operasi berhasil atau tidak sama sekali
    $conn->begin_transaction();

    try {
        // 1. Hapus detail_transaksi yang terkait terlebih dahulu
        $sql_delete_details = "DELETE FROM detail_transaksi WHERE transaksi_id = $transaksi_id";
        if (!$conn->query($sql_delete_details)) {
            throw new Exception("Error menghapus detail transaksi: " . $conn->error);
        }

        // 2. Kemudian, hapus transaksi utama
        $sql_delete_transaksi = "DELETE FROM transaksi WHERE transaksi_id = $transaksi_id";
        if (!$conn->query($sql_delete_transaksi)) {
            throw new Exception("Error menghapus transaksi: " . $conn->error);
        }

        // Jika kedua operasi berhasil, commit transaksi
        $conn->commit();
        header("Location: transaksi.php?status=deleted_success");
        exit();
    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        // Opsional: Arahkan kembali dengan pesan error
        // header("Location: transaksi.php?status=deleted_error&msg=" . urlencode($e->getMessage()));
        // exit();
    }
} else {
    // Jika ID tidak disediakan atau tidak valid
    echo "ID Transaksi tidak valid untuk dihapus.";
    // Opsional: Arahkan kembali
    // header("Location: transaksi.php?status=no_id");
    // exit();
}

$conn->close();
?>