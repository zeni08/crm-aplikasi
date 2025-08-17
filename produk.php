<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - CRM App</title>
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
                        <a class="nav-link active" aria-current="page" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php">Feedback</a>
                    <li class="nav-item">
                        <a class="nav-link" href="promosi.php">Promosi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="loyalty.php">Loyalty</a>
                    </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h3>Daftar Produk</h3>
                <a href="tambah_produk.php" class="btn btn-success mb-3">Tambah Produk Baru</a>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                                <th>Tanggal Ditambahkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Menginclude file koneksi database
                            include_once 'includes/db_connect.php';

                            // Query untuk mengambil semua data dari tabel produk
                            $sql = "SELECT produk_id, nama_produk, deskripsi, harga, stok, kategori, tanggal_ditambahkan FROM produk";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data setiap baris
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["produk_id"]. "</td>";
                                    echo "<td>" . $row["nama_produk"]. "</td>";
                                    echo "<td>" . ($row["deskripsi"] ? $row["deskripsi"] : '-') . "</td>";
                                    echo "<td>Rp " . number_format($row["harga"], 2, ',', '.') . "</td>"; // Format harga
                                    echo "<td>" . $row["stok"]. "</td>";
                                    echo "<td>" . ($row["kategori"] ? $row["kategori"] : '-') . "</td>";
                                    echo "<td>" . $row["tanggal_ditambahkan"]. "</td>";
                                    echo "<td>";
                                    // LINK EDIT PRODUK
                                    echo "<a href='edit_produk.php?id=" . $row["produk_id"] . "' class='btn btn-sm btn-info me-1'>Edit</a>";
                                    // LINK HAPUS PRODUK
                                    echo "<a href='hapus_produk.php?id=" . $row["produk_id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Tidak ada data produk.</td></tr>";
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