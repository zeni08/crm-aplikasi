<?php
// hapus_feedback.php

include_once 'includes/db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $feedback_id = $conn->real_escape_string($_GET['id']);

    // Query untuk menghapus data feedback
    $sql = "DELETE FROM feedback WHERE feedback_id = '$feedback_id'";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman feedback.php
        header("Location: feedback.php?status=deleted_success");
        exit();
    } else {
        // Jika terjadi error saat penghapusan
        echo "Error menghapus feedback: " . $sql . "<br>" . $conn->error;
        // Opsional: Arahkan kembali dengan pesan error
        // header("Location: feedback.php?status=deleted_error");
        // exit();
    }
} else {
    // Jika ID tidak disediakan atau tidak valid
    echo "ID Feedback tidak valid untuk dihapus.";
    // Opsional: Arahkan kembali
    // header("Location: feedback.php?status=no_id");
    // exit();
}

$conn->close();
?>