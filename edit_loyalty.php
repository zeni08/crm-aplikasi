<?php
include_once 'includes/db_connect.php';
include 'header.php';

$poin_id = $_GET['id'] ?? 0;
$loyalty_data = null;
$message = '';
$current_pelanggan_id = ''; // Untuk dropdown pelanggan

// Ambil daftar pelanggan untuk dropdown (jika akan membuat poin baru)
$pelanggan_list = [];
$sql_pelanggan = "SELECT pelanggan_id, nama FROM pelanggan ORDER BY nama ASC";
$result_pelanggan = $conn->query($sql_pelanggan);
if ($result_pelanggan->num_rows > 0) {
    while($row_pelanggan = $result_pelanggan->fetch_assoc()) {
        $pelanggan_list[] = $row_pelanggan;
    }
}

// Logika untuk menampilkan data saat GET request (pertama kali buka halaman)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($poin_id > 0) {
        $sql_select = "SELECT lp.poin_id, lp.pelanggan_id, p.nama AS nama_pelanggan, lp.jumlah_poin
                       FROM loyalty_poin lp
                       JOIN pelanggan p ON lp.pelanggan_id = p.pelanggan_id
                       WHERE lp.poin_id = '$poin_id'";
        $result_select = $conn->query($sql_select);

        if ($result_select->num_rows > 0) {
            $loyalty_data = $result_select->fetch_assoc();
            $current_pelanggan_id = $loyalty_data['pelanggan_id']; // Set pelanggan ID untuk dropdown
        } else {
            $message = "<div class='alert alert-warning'>Entri Poin Loyalty tidak ditemukan.</div>";
        }
    } else {
        // Jika tidak ada ID di URL, asumsikan ingin menambah poin baru untuk pelanggan
        $message = "<div class='alert alert-info'>Pilih pelanggan untuk menambah atau mengedit poin.</div>";
    }
}

// Logika untuk memproses form saat POST request (submit form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelanggan_id_form = $conn->real_escape_string($_POST['pelanggan_id']);
    $jumlah_poin = $conn->real_escape_string($_POST['jumlah_poin']);
    $form_poin_id = $conn->real_escape_string($_POST['poin_id'] ?? 0); // Ambil dari hidden field jika ada

    if (empty($pelanggan_id_form) || !is_numeric($pelanggan_id_form)) {
        $message = "<div class='alert alert-danger'>Pilih pelanggan yang valid.</div>";
    } elseif (!is_numeric($jumlah_poin) || $jumlah_poin < 0) {
        $message = "<div class='alert alert-danger'>Jumlah Poin harus berupa angka non-negatif.</div>";
    } else {
        // Cek apakah entri poin untuk pelanggan ini sudah ada
        $sql_check_existing = "SELECT poin_id FROM loyalty_poin WHERE pelanggan_id = '$pelanggan_id_form'";
        if ($form_poin_id > 0) { // Jika kita sedang mengedit entri yang sudah ada
            $sql_check_existing .= " AND poin_id != '$form_poin_id'";
        }
        $result_check = $conn->query($sql_check_existing);

        if ($result_check->num_rows > 0 && $form_poin_id == 0) { // Jika sudah ada poin untuk pelanggan ini DAN kita mencoba menambah baru (bukan edit)
            $message = "<div class='alert alert-warning'>Pelanggan ini sudah memiliki entri poin. Silakan edit entri yang sudah ada.</div>";
            // Ambil data yang sudah ada untuk ditampilkan di form
            $existing_poin_row = $result_check->fetch_assoc();
            $loyalty_data = ['poin_id' => $existing_poin_row['poin_id'], 'pelanggan_id' => $pelanggan_id_form, 'nama_pelanggan' => '', 'jumlah_poin' => ''];
            // Re-fetch existing data if needed, or simply set form values
            $current_pelanggan_id = $pelanggan_id_form;
        } else {
            if ($form_poin_id > 0) {
                // UPDATE data poin yang sudah ada
                $sql_update = "UPDATE loyalty_poin SET
                                jumlah_poin='$jumlah_poin',
                                tanggal_terakhir_update=CURRENT_TIMESTAMP
                                WHERE poin_id=$form_poin_id AND pelanggan_id=$pelanggan_id_form"; // Tambahkan pelanggan_id untuk keamanan

                if ($conn->query($sql_update) === TRUE) {
                    $message = "<div class='alert alert-success'>Poin Loyalty berhasil diperbarui! <a href='loyalty.php'>Kembali ke daftar poin</a></div>";
                    // Ambil data terbaru setelah sukses update
                    $sql_select_after_update = "SELECT lp.poin_id, lp.pelanggan_id, p.nama AS nama_pelanggan, lp.jumlah_poin
                                               FROM loyalty_poin lp JOIN pelanggan p ON lp.pelanggan_id = p.pelanggan_id
                                               WHERE lp.poin_id = $form_poin_id";
                    $result_after_update = $conn->query($sql_select_after_update);
                    if ($result_after_update->num_rows > 0) {
                        $loyalty_data = $result_after_update->fetch_assoc();
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Error memperbarui poin: " . $conn->error . "</div>";
                }
            } else {
                // INSERT data poin baru
                $sql_insert = "INSERT INTO loyalty_poin (pelanggan_id, jumlah_poin)
                               VALUES ('$pelanggan_id_form', '$jumlah_poin')";

                if ($conn->query($sql_insert) === TRUE) {
                    $message = "<div class='alert alert-success'>Poin Loyalty baru berhasil ditambahkan! <a href='loyalty.php'>Lihat daftar poin</a></div>";
                    // Bersihkan form setelah sukses tambah
                    $loyalty_data = null; // Agar form kosong
                    $current_pelanggan_id = '';
                } else {
                    $message = "<div class='alert alert-danger'>Error menambahkan poin: " . $conn->error . "</div>";
                }
            }
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
                    <h3><?php echo ($poin_id > 0 && $loyalty_data) ? 'Edit Poin Loyalty' : 'Tambah Poin Loyalty Baru'; ?></h3>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form action="edit_loyalty.php" method="POST">
                        <?php if ($poin_id > 0 && $loyalty_data): ?>
                            <input type="hidden" name="poin_id" value="<?php echo htmlspecialchars($loyalty_data['poin_id']); ?>">
                            <div class="mb-3">
                                <label for="nama_pelanggan_display" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="nama_pelanggan_display" value="<?php echo htmlspecialchars($loyalty_data['nama_pelanggan']); ?>" disabled>
                                <input type="hidden" name="pelanggan_id" value="<?php echo htmlspecialchars($loyalty_data['pelanggan_id']); ?>">
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="pelanggan_id" class="form-label">Pilih Pelanggan <span class="text-danger">*</span></label>
                                <select class="form-select" id="pelanggan_id" name="pelanggan_id" required>
                                    <option value="">Pilih Pelanggan</option>
                                    <?php foreach ($pelanggan_list as $pelanggan_item): ?>
                                        <option value="<?php echo htmlspecialchars($pelanggan_item['pelanggan_id']); ?>"
                                            <?php echo ($current_pelanggan_id == $pelanggan_item['pelanggan_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($pelanggan_item['nama']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="jumlah_poin" class="form-label">Jumlah Poin <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_poin" name="jumlah_poin" value="<?php echo htmlspecialchars($loyalty_data['jumlah_poin'] ?? ''); ?>" required min="0">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo ($poin_id > 0 && $loyalty_data) ? 'Update Poin' : 'Tambah Poin'; ?></button>
                        <a href="loyalty.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>