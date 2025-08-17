<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - CRM App</title>
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
                        <a class="nav-link active" aria-current="page" href="pelanggan.php">Pelanggan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Transaksi</a>
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
                        <h3>Edit Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        include_once 'includes/db_connect.php';

                        $pelanggan_id = $_GET['id'] ?? 0; // Ambil ID dari URL, default 0 jika tidak ada
                        $pelanggan = null;
                        $message = '';

                        // Jika form disubmit (metode POST) untuk update data
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $pelanggan_id = $conn->real_escape_string($_POST['pelanggan_id']); // Pastikan ID juga diambil dari hidden field
                            $nama = $conn->real_escape_string($_POST['nama']);
                            $email = $conn->real_escape_string($_POST['email']);
                            $no_hp = $conn->real_escape_string($_POST['no_hp']);
                            $alamat = $conn->real_escape_string($_POST['alamat']);

                            $sql_update = "UPDATE pelanggan SET nama='$nama', email='$email', no_hp='$no_hp', alamat='$alamat' WHERE pelanggan_id=$pelanggan_id";

                            if ($conn->query($sql_update) === TRUE) {
                                $message = "<div class='alert alert-success'>Data pelanggan berhasil diperbarui! <a href='pelanggan.php'>Kembali ke daftar pelanggan</a></div>";
                                // Setelah update, ambil kembali data terbaru untuk ditampilkan di form
                                $sql_select_after_update = "SELECT * FROM pelanggan WHERE pelanggan_id = $pelanggan_id";
                                $result_after_update = $conn->query($sql_select_after_update);
                                if ($result_after_update->num_rows > 0) {
                                    $pelanggan = $result_after_update->fetch_assoc();
                                }
                            } else {
                                $message = "<div class='alert alert-danger'>Error memperbarui data: " . $conn->error . "</div>";
                            }
                        } else {
                            // Jika halaman pertama kali dimuat (metode GET), ambil data pelanggan
                            if ($pelanggan_id > 0) {
                                $sql_select = "SELECT * FROM pelanggan WHERE pelanggan_id = $pelanggan_id";
                                $result_select = $conn->query($sql_select);

                                if ($result_select->num_rows > 0) {
                                    $pelanggan = $result_select->fetch_assoc();
                                } else {
                                    $message = "<div class='alert alert-warning'>Pelanggan tidak ditemukan.</div>";
                                }
                            } else {
                                $message = "<div class='alert alert-danger'>ID Pelanggan tidak valid.</div>";
                            }
                        }
                        $conn->close(); // Tutup koneksi setelah semua operasi database selesai
                        ?>

                        <?php echo $message; // Tampilkan pesan jika ada ?>

                        <?php if ($pelanggan): // Tampilkan form hanya jika pelanggan ditemukan ?>
                        <form action="edit_pelanggan.php" method="POST">
                            <input type="hidden" name="pelanggan_id" value="<?php echo htmlspecialchars($pelanggan['pelanggan_id']); ?>">

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($pelanggan['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($pelanggan['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($pelanggan['no_hp']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($pelanggan['alamat']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                            <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
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