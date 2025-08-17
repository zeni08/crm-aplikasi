<?php
include_once 'includes/db_connect.php';
include 'header.php';

$message = '';
$pelanggan_id = '';
$transaksi_id = '';
$isi_feedback = '';
$rating = '';
$status = 'Pending'; // Default status

// Ambil daftar pelanggan untuk dropdown
$pelanggan_list = [];
$sql_pelanggan = "SELECT pelanggan_id, nama FROM pelanggan ORDER BY nama ASC";
$result_pelanggan = $conn->query($sql_pelanggan);
if ($result_pelanggan->num_rows > 0) {
    while($row_pelanggan = $result_pelanggan->fetch_assoc()) {
        $pelanggan_list[] = $row_pelanggan;
    }
}

// Ambil daftar transaksi untuk dropdown (opsional, bisa NULL)
$transaksi_list = [];
$sql_transaksi = "SELECT transaksi_id, tanggal, total FROM transaksi ORDER BY tanggal DESC LIMIT 50"; // Ambil 50 transaksi terakhir
$result_transaksi = $conn->query($sql_transaksi);
if ($result_transaksi->num_rows > 0) {
    while($row_transaksi = $result_transaksi->fetch_assoc()) {
        $transaksi_list[] = $row_transaksi;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelanggan_id = $conn->real_escape_string($_POST['pelanggan_id']);
    $transaksi_id = !empty($_POST['transaksi_id']) ? $conn->real_escape_string($_POST['transaksi_id']) : NULL; // Bisa NULL
    $isi_feedback = $conn->real_escape_string($_POST['isi_feedback']);
    $rating = !empty($_POST['rating']) ? $conn->real_escape_string($_POST['rating']) : NULL; // Bisa NULL
    $status = $conn->real_escape_string($_POST['status']);

    // Validasi input
    if (empty($pelanggan_id) || !is_numeric($pelanggan_id)) {
        $message = "<div class='alert alert-danger'>Pilih pelanggan yang valid.</div>";
    } elseif (empty($isi_feedback)) {
        $message = "<div class='alert alert-danger'>Isi feedback tidak boleh kosong.</div>";
    } elseif (!is_null($rating) && (!is_numeric($rating) || $rating < 1 || $rating > 5)) {
        $message = "<div class='alert alert-danger'>Rating harus antara 1 dan 5.</div>";
    } else {
        // Handle NULL values properly for SQL INSERT
        $transaksi_id_sql = ($transaksi_id === NULL) ? "NULL" : "'$transaksi_id'";
        $rating_sql = ($rating === NULL) ? "NULL" : "'$rating'";

        $sql = "INSERT INTO feedback (pelanggan_id, transaksi_id, isi_feedback, rating, status) 
                VALUES ('$pelanggan_id', $transaksi_id_sql, '$isi_feedback', $rating_sql, '$status')";

        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>Feedback berhasil ditambahkan! <a href='feedback.php'>Lihat daftar feedback</a></div>";
            // Bersihkan form setelah sukses
            $pelanggan_id = '';
            $transaksi_id = '';
            $isi_feedback = '';
            $rating = '';
            $status = 'Pending';
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}
$conn->close();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Tambah Feedback Baru</h3>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form action="tambah_feedback.php" method="POST">
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pelanggan_id" name="pelanggan_id" required>
                                <option value="">Pilih Pelanggan</option>
                                <?php foreach ($pelanggan_list as $pelanggan_item): ?>
                                    <option value="<?php echo htmlspecialchars($pelanggan_item['pelanggan_id']); ?>"
                                        <?php echo ($pelanggan_id == $pelanggan_item['pelanggan_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pelanggan_item['nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transaksi_id" class="form-label">Transaksi Terkait (Opsional)</label>
                            <select class="form-select" id="transaksi_id" name="transaksi_id">
                                <option value="">Tidak Ada Transaksi</option>
                                <?php foreach ($transaksi_list as $transaksi_item): ?>
                                    <option value="<?php echo htmlspecialchars($transaksi_item['transaksi_id']); ?>"
                                        <?php echo ($transaksi_id == $transaksi_item['transaksi_id']) ? 'selected' : ''; ?>>
                                        ID: <?php echo htmlspecialchars($transaksi_item['transaksi_id']); ?> (Tanggal: <?php echo htmlspecialchars($transaksi_item['tanggal']); ?>, Total: Rp <?php echo number_format($transaksi_item['total'], 2, ',', '.'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="isi_feedback" class="form-label">Isi Feedback <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_feedback" name="isi_feedback" rows="5" required><?php echo htmlspecialchars($isi_feedback); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5, Opsional)</label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="">Tidak Ada Rating</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($rating == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Bintang</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Feedback <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <?php
                                $status_options = ['Pending', 'Reviewed', 'Resolved', 'Archived'];
                                foreach ($status_options as $option) {
                                    echo "<option value='" . htmlspecialchars($option) . "'" . (($status == $option) ? ' selected' : '') . ">" . htmlspecialchars($option) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Feedback</button>
                        <a href="feedback.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>