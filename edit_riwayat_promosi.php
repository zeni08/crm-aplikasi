<?php
include_once 'includes/db_connect.php';
include 'header.php';

$riwayat_id = $_GET['id'] ?? 0;
$riwayat_data = null;
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

// Ambil daftar promosi untuk dropdown
$promosi_list = [];
$sql_promosi = "SELECT promosi_id, nama_promosi, kode_promosi FROM promosi ORDER BY nama_promosi ASC";
$result_promosi = $conn->query($sql_promosi);
if ($result_promosi->num_rows > 0) {
    while($row_promosi = $result_promosi->fetch_assoc()) {
        $promosi_list[] = $row_promosi;
    }
}

// Logika untuk menampilkan data saat GET request (pertama kali buka halaman)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($riwayat_id > 0) {
        $sql_select = "SELECT * FROM riwayat_promosi_pelanggan WHERE riwayat_id = '$riwayat_id'";
        $result_select = $conn->query($sql_select);

        if ($result_select->num_rows > 0) {
            $riwayat_data = $result_select->fetch_assoc();
        } else {
            $message = "<div class='alert alert-warning'>Riwayat Promosi tidak ditemukan.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>ID Riwayat Promosi tidak valid.</div>";
    }
}

// Jika form disubmit (metode POST) untuk update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riwayat_id = $conn->real_escape_string($_POST['riwayat_id']);
    $pelanggan_id = $conn->real_escape_string($_POST['pelanggan_id']);
    $promosi_id = $conn->real_escape_string($_POST['promosi_id']);
    $tanggal_digunakan = $conn->real_escape_string($_POST['tanggal_digunakan']);
    $jumlah_diskon_didapat = $conn->real_escape_string($_POST['jumlah_diskon_didapat']);

    // Validasi input
    if (empty($pelanggan_id) || !is_numeric($pelanggan_id)) {
        $message = "<div class='alert alert-danger'>Pilih pelanggan yang valid.</div>";
    } elseif (empty($promosi_id) || !is_numeric($promosi_id)) {
        $message = "<div class='alert alert-danger'>Pilih promosi yang valid.</div>";
    } elseif (!is_numeric($jumlah_diskon_didapat) || $jumlah_diskon_didapat < 0) {
        $message = "<div class='alert alert-danger'>Jumlah Diskon Didapat harus berupa angka non-negatif.</div>";
    } else {
        $sql_update = "UPDATE riwayat_promosi_pelanggan SET
                        pelanggan_id='$pelanggan_id',
                        promosi_id='$promosi_id',
                        tanggal_digunakan='$tanggal_digunakan',
                        jumlah_diskon_didapat='$jumlah_diskon_didapat'
                        WHERE riwayat_id=$riwayat_id";

        if ($conn->query($sql_update) === TRUE) {
            $message = "<div class='alert alert-success'>Riwayat Promosi berhasil diperbarui! <a href='riwayat_promosi.php'>Kembali ke daftar riwayat</a></div>";
            // Ambil data terbaru setelah sukses update agar form terisi dengan data terbaru
            $sql_select_after_update = "SELECT * FROM riwayat_promosi_pelanggan WHERE riwayat_id = $riwayat_id";
            $result_after_update = $conn->query($sql_select_after_update);
            if ($result_after_update->num_rows > 0) {
                $riwayat_data = $result_after_update->fetch_assoc();
            }
        } else {
            $message = "<div class='alert alert-danger'>Error memperbarui data: " . $conn->error . "</div>";
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
                    <h3>Edit Riwayat Promosi</h3>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <?php if ($riwayat_data): ?>
                    <form action="edit_riwayat_promosi.php" method="POST">
                        <input type="hidden" name="riwayat_id" value="<?php echo htmlspecialchars($riwayat_data['riwayat_id']); ?>">

                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pelanggan_id" name="pelanggan_id" required>
                                <option value="">Pilih Pelanggan</option>
                                <?php foreach ($pelanggan_list as $pelanggan_item): ?>
                                    <option value="<?php echo htmlspecialchars($pelanggan_item['pelanggan_id']); ?>"
                                        <?php echo ($riwayat_data['pelanggan_id'] == $pelanggan_item['pelanggan_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pelanggan_item['nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="promosi_id" class="form-label">Promosi <span class="text-danger">*</span></label>
                            <select class="form-select" id="promosi_id" name="promosi_id" required>
                                <option value="">Pilih Promosi</option>
                                <?php foreach ($promosi_list as $promosi_item): ?>
                                    <option value="<?php echo htmlspecialchars($promosi_item['promosi_id']); ?>"
                                        <?php echo ($riwayat_data['promosi_id'] == $promosi_item['promosi_id']) ? 'selected' : ''; ?>>
                                        ID: <?php echo htmlspecialchars($promosi_item['promosi_id']); ?> - <?php echo htmlspecialchars($promosi_item['nama_promosi']); ?> (<?php echo htmlspecialchars($promosi_item['kode_promosi']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_digunakan" class="form-label">Tanggal Digunakan <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="tanggal_digunakan" name="tanggal_digunakan" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($riwayat_data['tanggal_digunakan']))); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_diskon_didapat" class="form-label">Jumlah Diskon Didapat <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="jumlah_diskon_didapat" name="jumlah_diskon_didapat" value="<?php echo htmlspecialchars($riwayat_data['jumlah_diskon_didapat']); ?>" required min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Riwayat</button>
                        <a href="riwayat_promosi.php" class="btn btn-secondary">Batal</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>