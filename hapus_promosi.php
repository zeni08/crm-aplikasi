<?php
// hapus_promosi.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $promosi_id = $conn->real_escape_string($_GET['id']);

    // Mulai transaksi database jika perlu menghapus dari tabel lain
    // Jika riwayat_promosi_pelanggan memiliki ON DELETE CASCADE, maka ini tidak wajib
    // Tapi praktik baik jika ada tabel lain yang mungkin terpengaruh dan tidak ada CASCADE
    $conn->begin_transaction();

    try {
        // Opsional: Hapus riwayat_promosi_pelanggan yang terkait jika tidak ada ON DELETE CASCADE
        // $sql_delete_riwayat = "DELETE FROM riwayat_promosi_pelanggan WHERE promosi_id = '$promosi_id'";
        // if (!$conn->query($sql_delete_riwayat)) {
        //     throw new Exception("Error menghapus riwayat promosi: " . $conn->error);
        // }

        // Hapus promosi utama
        $sql_delete_promosi = "DELETE FROM promosi WHERE promosi_id = '$promosi_id'";
        if (!$conn->query($sql_delete_promosi)) {
            throw new Exception("Error menghapus promosi: " . $conn->error);
        }

        $conn->commit();
        header("Location: promosi.php?status=deleted_success");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        // header("Location: promosi.php?status=deleted_error&msg=" . urlencode($e->getMessage()));
        // exit();
    }
} else {
    echo "ID Promosi tidak valid untuk dihapus.";
    // header("Location: promosi.php?status=no_id");
    // exit();
}

$conn->close();
?>