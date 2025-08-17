<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - PRIMA FRESHMART CABANG CAKUNG</title>
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
                        <a class="nav-link" href="#">Feedback</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>Tambah Produk Baru</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        include_once 'includes/db_connect.php';

                        $message = '';
                        $nama_produk = $deskripsi = $harga = $stok = $kategori = ''; // Inisialisasi variabel untuk form

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
                            $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
                            $harga = $conn->real_escape_string($_POST['harga']);
                            $stok = $conn->real_escape_string($_POST['stok']);
                            $kategori = $conn->real_escape_string($_POST['kategori']);

                            // Pastikan harga dan stok adalah angka
                            if (!is_numeric($harga) || $harga < 0) {
                                $message = "<div class='alert alert-danger'>Harga harus berupa angka positif.</div>";
                            } elseif (!is_numeric($stok) || $stok < 0) {
                                $message = "<div class='alert alert-danger'>Stok harus berupa angka non-negatif.</div>";
                            } else {
                                $sql = "INSERT INTO produk (nama_produk, deskripsi, harga, stok, kategori) 
                                        VALUES ('$nama_produk', '$deskripsi', '$harga', '$stok', '$kategori')";

                                if ($conn->query($sql) === TRUE) {
                                    $message = "<div class='alert alert-success'>Produk baru berhasil ditambahkan! <a href='produk.php'>Lihat daftar produk</a></div>";
                                    // Bersihkan form setelah sukses
                                    $nama_produk = $deskripsi = $harga = $stok = $kategori = '';
                                } else {
                                    $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                }
                            }
                            $conn->close();
                        }
                        ?>

                        <?php echo $message; ?>

                        <form action="tambah_produk.php" method="POST">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($nama_produk); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($harga); ?>" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($stok); ?>" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                            <a href="produk.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>