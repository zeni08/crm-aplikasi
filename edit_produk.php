<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - CRM App</title>
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
                        <h3>Edit Produk</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        include_once 'includes/db_connect.php';

                        $produk_id = $_GET['id'] ?? 0; // Ambil ID dari URL, default 0
                        $produk = null;
                        $message = '';

                        // Jika form disubmit (metode POST) untuk update data
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $produk_id = $conn->real_escape_string($_POST['produk_id']);
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
                                $sql_update = "UPDATE produk SET 
                                                nama_produk='$nama_produk', 
                                                deskripsi='$deskripsi', 
                                                harga='$harga', 
                                                stok='$stok', 
                                                kategori='$kategori' 
                                                WHERE produk_id=$produk_id";

                                if ($conn->query($sql_update) === TRUE) {
                                    $message = "<div class='alert alert-success'>Data produk berhasil diperbarui! <a href='produk.php'>Kembali ke daftar produk</a></div>";
                                    // Setelah update, ambil kembali data terbaru untuk ditampilkan di form
                                    $sql_select_after_update = "SELECT * FROM produk WHERE produk_id = $produk_id";
                                    $result_after_update = $conn->query($sql_select_after_update);
                                    if ($result_after_update->num_rows > 0) {
                                        $produk = $result_after_update->fetch_assoc();
                                    }
                                } else {
                                    $message = "<div class='alert alert-danger'>Error memperbarui data: " . $conn->error . "</div>";
                                }
                            }
                            // Setelah POST, koneksi ditutup di akhir skrip
                        } else {
                            // Jika halaman pertama kali dimuat (GET), ambil data produk
                            if ($produk_id > 0) {
                                $sql_select = "SELECT * FROM produk WHERE produk_id = $produk_id";
                                $result_select = $conn->query($sql_select);

                                if ($result_select->num_rows > 0) {
                                    $produk = $result_select->fetch_assoc();
                                } else {
                                    $message = "<div class='alert alert-warning'>Produk tidak ditemukan.</div>";
                                }
                            } else {
                                $message = "<div class='alert alert-danger'>ID Produk tidak valid.</div>";
                            }
                        }
                        $conn->close(); // Tutup koneksi setelah semua operasi database selesai
                        ?>

                        <?php echo $message; ?>

                        <?php if ($produk): ?>
                        <form action="edit_produk.php" method="POST">
                            <input type="hidden" name="produk_id" value="<?php echo htmlspecialchars($produk['produk_id']); ?>">

                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($produk['deskripsi']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($produk['harga']); ?>" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($produk['stok']); ?>" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($produk['kategori']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Produk</button>
                            <a href="produk.php" class="btn btn-secondary">Batal</a>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>