<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan Baru - CRM App</title>
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
                        <h3>Tambah Pelanggan Baru</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Menginclude file koneksi database
                        include_once 'includes/db_connect.php';

                        $message = ''; // Variabel untuk menyimpan pesan sukses atau error

                        // Cek apakah form sudah disubmit
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            // Ambil data dari form dan sanitasi input
                            $nama = $conn->real_escape_string($_POST['nama']);
                            $email = $conn->real_escape_string($_POST['email']);
                            $no_hp = $conn->real_escape_string($_POST['no_hp']);
                            $alamat = $conn->real_escape_string($_POST['alamat']);

                            // Query untuk menyimpan data ke tabel pelanggan
                            $sql = "INSERT INTO pelanggan (nama, email, no_hp, alamat) VALUES ('$nama', '$email', '$no_hp', '$alamat')";

                            if ($conn->query($sql) === TRUE) {
                                $message = "<div class='alert alert-success'>Data pelanggan baru berhasil ditambahkan! <a href='pelanggan.php'>Lihat daftar pelanggan</a></div>";
                                // Bersihkan nilai form setelah sukses
                                $nama = $email = $no_hp = $alamat = '';
                            } else {
                                $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                            }

                            // Menutup koneksi database
                            $conn->close();
                        }
                        ?>

                        <?php echo $message; // Tampilkan pesan jika ada ?>

                        <form action="tambah_pelanggan.php" method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($no_hp ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($alamat ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                            <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>