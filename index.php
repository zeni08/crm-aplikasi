<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM App - Dashboard</title>
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
                        <a class="nav-link active" aria-current="page" href="index.php">Dashboard</a>
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
                <div class="alert alert-success" role="alert">
                    Halo! Selamat datang di Aplikasi PRIMA FRESHMART CABANG CAKUNG.
                </div>
                <h3>Dashboard</h3>
                <p>Gunakan navigasi di atas untuk menjelajahi fitur-fitur PRIMA FRESHMART CABANG CAKUNG.</p>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Manajemen Pelanggan</h5>
                                <p class="card-text">Kelola data pelanggan Anda.</p>
                                <a href="pelanggan.php" class="btn btn-primary">Lihat Pelanggan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Manajemen Produk</h5>
                                <p class="card-text">Kelola daftar produk Anda.</p>
                                <a href="produk.php" class="btn btn-info ">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Manajemen Transaksi</h5>
                                <p class="card-text">Lihat dan kelola transaksi pelanggan.</p>
                                <a href="transaksi.php" class="btn btn-warning ">Lihat Transaksi </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>