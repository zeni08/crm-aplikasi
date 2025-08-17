<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - CRM App</title>
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
                        <a class="nav-link active" aria-current="page" href="transaksi.php">Transaksi</a>
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
                        <h3>Edit Transaksi</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        include_once 'includes/db_connect.php';

                        $transaksi_id = $_GET['id'] ?? 0;
                        $transaksi = null;
                        $message = '';

                        // Ambil daftar pelanggan untuk dropdown
                        $pelanggan_list = [];
                        $sql_pelanggan = "SELECT pelanggan_id, nama FROM pelanggan ORDER BY nama ASC";
                        $result_pelanggan = $conn->query($sql_pelanggan);
                        if ($result_pelanggan->num_rows > 0) {
                            while($row_pelanggan = $result_pelanggan->fetch_assoc()) {
                                $pelanggan_list[] = $row_pelanggan;
                            }
                        }

                        // Jika form disubmit (metode POST) untuk update data
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $transaksi_id = $conn->real_escape_string($_POST['transaksi_id']);
                            $pelanggan_id = $conn->real_escape_string($_POST['pelanggan_id']);
                            $tanggal = $conn->real_escape_string($_POST['tanggal']);
                            $total = $conn->real_escape_string($_POST['total']);
                            $metode_pembayaran = $conn->real_escape_string($_POST['metode_pembayaran']);
                            $status_transaksi = $conn->real_escape_string($_POST['status_transaksi']);

                            // Validasi input
                            if (empty($pelanggan_id) || !is_numeric($pelanggan_id)) {
                                $message = "<div class='alert alert-danger'>Pilih pelanggan yang valid.</div>";
                            } elseif (!is_numeric($total) || $total < 0) {
                                $message = "<div class='alert alert-danger'>Total harus berupa angka non-negatif.</div>";
                            } else {
                                $sql_update = "UPDATE transaksi SET 
                                                pelanggan_id='$pelanggan_id', 
                                                tanggal='$tanggal', 
                                                total='$total', 
                                                metode_pembayaran='$metode_pembayaran', 
                                                status_transaksi='$status_transaksi' 
                                                WHERE transaksi_id=$transaksi_id";

                                if ($conn->query($sql_update) === TRUE) {
                                    $message = "<div class='alert alert-success'>Data transaksi berhasil diperbarui! <a href='transaksi.php'>Kembali ke daftar transaksi</a></div>";
                                    // Setelah update, ambil kembali data terbaru untuk ditampilkan di form
                                    $sql_select_after_update = "SELECT * FROM transaksi WHERE transaksi_id = $transaksi_id";
                                    $result_after_update = $conn->query($sql_select_after_update);
                                    if ($result_after_update->num_rows > 0) {
                                        $transaksi = $result_after_update->fetch_assoc();
                                    }
                                } else {
                                    $message = "<div class='alert alert-danger'>Error memperbarui data: " . $conn->error . "</div>";
                                }
                            }
                        } else {
                            // Jika halaman pertama kali dimuat (GET), ambil data transaksi
                            if ($transaksi_id > 0) {
                                $sql_select = "SELECT * FROM transaksi WHERE transaksi_id = $transaksi_id";
                                $result_select = $conn->query($sql_select);

                                if ($result_select->num_rows > 0) {
                                    $transaksi = $result_select->fetch_assoc();
                                } else {
                                    $message = "<div class='alert alert-warning'>Transaksi tidak ditemukan.</div>";
                                }
                            } else {
                                $message = "<div class='alert alert-danger'>ID Transaksi tidak valid.</div>";
                            }
                        }
                        $conn->close();
                        ?>

                        <?php echo $message; ?>

                        <?php if ($transaksi): ?>
                        <form action="edit_transaksi.php" method="POST">
                            <input type="hidden" name="transaksi_id" value="<?php echo htmlspecialchars($transaksi['transaksi_id']); ?>">

                            <div class="mb-3">
                                <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                                <select class="form-select" id="pelanggan_id" name="pelanggan_id" required>
                                    <option value="">Pilih Pelanggan</option>
                                    <?php foreach ($pelanggan_list as $pelanggan_item): ?>
                                        <option value="<?php echo htmlspecialchars($pelanggan_item['pelanggan_id']); ?>"
                                            <?php echo ($transaksi['pelanggan_id'] == $pelanggan_item['pelanggan_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($pelanggan_item['nama']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($transaksi['tanggal']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="total" class="form-label">Total Transaksi <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="total" name="total" value="<?php echo htmlspecialchars($transaksi['total']); ?>" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <input type="text" class="form-control" id="metode_pembayaran" name="metode_pembayaran" value="<?php echo htmlspecialchars($transaksi['metode_pembayaran']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="status_transaksi" class="form-label">Status Transaksi <span class="text-danger">*</span></label>
                                <select class="form-select" id="status_transaksi" name="status_transaksi" required>
                                    <?php
                                    $status_options = ['Pending', 'Completed', 'Cancelled', 'Refunded'];
                                    foreach ($status_options as $option) {
                                        echo "<option value='" . htmlspecialchars($option) . "'" . (($transaksi['status_transaksi'] == $option) ? 'selected' : '') . ">" . htmlspecialchars($option) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Transaksi</button>
                            <a href="transaksi.php" class="btn btn-secondary">Batal</a>
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