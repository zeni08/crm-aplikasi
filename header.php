<?php
// header.php
// File ini akan digunakan untuk bagian atas setiap halaman (termasuk navbar)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM App</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">PRIMA FRESHMART CABANG CAKUNG</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'promosi.php' || basename($_SERVER['PHP_SELF']) == 'tambah_promosi.php' || basename($_SERVER['PHP_SELF']) == 'edit_promosi.php') ? 'active' : ''; ?>" href="promosi.php">Promosi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'riwayat_promosi.php') ? 'active' : ''; ?>" href="riwayat_promosi.php">Riwayat Promosi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'loyalty.php') ? 'active' : ''; ?>" href="loyalty.php">Loyalty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pelanggan.php' || basename($_SERVER['PHP_SELF']) == 'tambah_pelanggan.php' || basename($_SERVER['PHP_SELF']) == 'edit_pelanggan.php') ? 'active' : ''; ?>" href="pelanggan.php">Pelanggan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'produk.php' || basename($_SERVER['PHP_SELF']) == 'tambah_produk.php' || basename($_SERVER['PHP_SELF']) == 'edit_produk.php') ? 'active' : ''; ?>" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'transaksi.php' || basename($_SERVER['PHP_SELF']) == 'tambah_transaksi.php' || basename($_SERVER['PHP_SELF']) == 'edit_transaksi.php' || basename($_SERVER['PHP_SELF']) == 'detail_transaksi.php') ? 'active' : ''; ?>" href="transaksi.php">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php' || basename($_SERVER['PHP_SELF']) == 'tambah_feedback.php' || basename($_SERVER['PHP_SELF']) == 'edit_feedback.php') ? 'active' : ''; ?>" href="feedback.php">Feedback</a>
                    </li>
                    </ul>
            </div>
        </div>
    </nav>