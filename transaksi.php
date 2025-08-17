<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - CRM App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pelanggan.php">Pelanggan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php">Feedback</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="promosi.php">Promosi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="loyalty.php">Loyalty</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
            <h3>Daftar Transaksi</h3>
            <a href="tambah_transaksi.php" class="btn btn-success mb-3">Tambah Transaksi Baru</a>
            <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Menginclude file koneksi database
                            include_once 'includes/db_connect.php';

                            // Query untuk mengambil data transaksi dengan JOIN ke tabel pelanggan
                            $sql = "SELECT t.transaksi_id, p.nama AS nama_pelanggan, t.tanggal, t.total, t.metode_pembayaran, t.status_transaksi 
                                    FROM transaksi t
                                    JOIN pelanggan p ON t.pelanggan_id = p.pelanggan_id
                                    ORDER BY t.tanggal DESC"; // Urutkan berdasarkan tanggal terbaru
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data setiap baris
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["transaksi_id"]. "</td>";
                                    echo "<td>" . $row["nama_pelanggan"]. "</td>"; // Nama pelanggan dari JOIN
                                    echo "<td>" . $row["tanggal"]. "</td>";
                                    echo "<td>Rp " . number_format($row["total"], 2, ',', '.') . "</td>"; // Format total
                                    echo "<td>" . ($row["metode_pembayaran"] ? $row["metode_pembayaran"] : '-') . "</td>";
                                    echo "<td>" . $row["status_transaksi"]. "</td>";
                                    echo "<td>";
                                // LINK DETAIL TRANSAKSI (akan kita buat nanti)
                                echo "<a href='detail_transaksi.php?id=" . $row["transaksi_id"] . "' class='btn btn-sm btn-info me-1'>Detail</a>";
                                // LINK HAPUS TRANSAKSI
                                echo "<a href='hapus_transaksi.php?id=" . $row["transaksi_id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus transaksi ini? Ini juga akan menghapus detail produk dalam transaksi ini.\")'>Hapus</a>";
                                echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data transaksi.</td></tr>";
                            }
                            $conn->close(); // Menutup koneksi database
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>