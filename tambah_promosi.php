<?php
include_once 'includes/db_connect.php';
include 'header.php';

$message = '';
$nama_promosi = '';
$deskripsi = '';
$kode_promosi = '';
$tipe_diskon = 'persentase'; // Default
$nilai_diskon = '';
$tanggal_mulai = date('Y-m-d'); // Default tanggal hari ini
$tanggal_berakhir = date('Y-m-d', strtotime('+1 month')); // Default 1 bulan dari sekarang
$min_pembelian = 0;
$status = 'Aktif'; // Default status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_promosi = $conn->real_escape_string($_POST['nama_promosi']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $kode_promosi = !empty($_POST['kode_promosi']) ? $conn->real_escape_string($_POST['kode_promosi']) : NULL; // Bisa NULL
    $tipe_diskon = $conn->real_escape_string($_POST['tipe_diskon']);
    $nilai_diskon = $conn->real_escape_string($_POST['nilai_diskon']);
    $tanggal_mulai = $conn->real_escape_string($_POST['tanggal_mulai']);
    $tanggal_berakhir = $conn->real_escape_string($_POST['tanggal_berakhir']);
    $min_pembelian = $conn->real_escape_string($_POST['min_pembelian']);
    $status = $conn->real_escape_string($_POST['status']);

    // Validasi input
    if (empty($nama_promosi)) {
        $message = "<div class='alert alert-danger'>Nama Promosi tidak boleh kosong.</div>";
    } elseif (!is_numeric($nilai_diskon) || $nilai_diskon < 0) {
        $message = "<div class='alert alert-danger'>Nilai Diskon harus berupa angka positif.</div>";
    } elseif ($tipe_diskon == 'persentase' && ($nilai_diskon > 1 || $nilai_diskon < 0)) {
        // Jika persentase, nilai harus antara 0 dan 1
        $message = "<div class='alert alert-danger'>Untuk diskon persentase, nilai diskon harus antara 0 dan 1 (misal: 0.1 untuk 10%).</div>";
    } elseif (!is_numeric($min_pembelian) || $min_pembelian < 0) {
        $message = "<div class='alert alert-danger'>Minimum Pembelian harus berupa angka non-negatif.</div>";
    } elseif (strtotime($tanggal_mulai) > strtotime($tanggal_berakhir)) {
        $message = "<div class='alert alert-danger'>Tanggal Mulai tidak boleh lebih dari Tanggal Berakhir.</div>";
    } else {
        // Pastikan nilai diskon untuk persentase disimpan sebagai desimal (misal 0.1 bukan 10)
        // Dan untuk nominal sebagai angka biasa
        if ($tipe_diskon == 'persentase' && $nilai_diskon > 1) { // Jika user input 10 for 10%
            $nilai_diskon = $nilai_diskon / 100;
        }

        // Handle NULL for kode_promosi
        $kode_promosi_sql = ($kode_promosi === NULL) ? "NULL" : "'$kode_promosi'";

        $sql = "INSERT INTO promosi (nama_promosi, deskripsi, kode_promosi, tipe_diskon, nilai_diskon, tanggal_mulai, tanggal_berakhir, min_pembelian, status) 
                VALUES ('$nama_promosi', '$deskripsi', $kode_promosi_sql, '$tipe_diskon', '$nilai_diskon', '$tanggal_mulai', '$tanggal_berakhir', '$min_pembelian', '$status')";

        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>Promosi baru berhasil ditambahkan! <a href='promosi.php'>Lihat daftar promosi</a></div>";
            // Bersihkan form setelah sukses
            $nama_promosi = $deskripsi = $kode_promosi = '';
            $tipe_diskon = 'persentase';
            $nilai_diskon = '';
            $tanggal_mulai = date('Y-m-d');
            $tanggal_berakhir = date('Y-m-d', strtotime('+1 month'));
            $min_pembelian = 0;
            $status = 'Aktif';
        } else {
            // Periksa jika error karena kode_promosi duplikat (UNIQUE constraint)
            if ($conn->errno == 1062 && strpos($conn->error, 'kode_promosi') !== false) {
                 $message = "<div class='alert alert-danger'>Error: Kode promosi '$kode_promosi' sudah ada. Mohon gunakan kode lain.</div>";
            } else {
                 $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
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
                    <h3>Tambah Promosi Baru</h3>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form action="tambah_promosi.php" method="POST">
                        <div class="mb-3">
                            <label for="nama_promosi" class="form-label">Nama Promosi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_promosi" name="nama_promosi" value="<?php echo htmlspecialchars($nama_promosi); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="kode_promosi" class="form-label">Kode Promosi (Opsional)</label>
                            <input type="text" class="form-control" id="kode_promosi" name="kode_promosi" value="<?php echo htmlspecialchars($kode_promosi); ?>">
                            <div class="form-text">Contoh: DISKON10, GRATISONGKIR</div>
                        </div>
                        <div class="mb-3">
                            <label for="tipe_diskon" class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipe_diskon" name="tipe_diskon" required>
                                <option value="persentase" <?php echo ($tipe_diskon == 'persentase') ? 'selected' : ''; ?>>Persentase (%)</option>
                                <option value="nominal" <?php echo ($tipe_diskon == 'nominal') ? 'selected' : ''; ?>>Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nilai_diskon" class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="nilai_diskon" name="nilai_diskon" value="<?php echo htmlspecialchars($nilai_diskon); ?>" required min="0">
                            <div class="form-text" id="nilaiDiskonHelp">
                                Untuk persentase: masukkan desimal (misal 0.1 untuk 10%). Untuk nominal: masukkan angka (misal 10000).
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo htmlspecialchars($tanggal_mulai); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" value="<?php echo htmlspecialchars($tanggal_berakhir); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="min_pembelian" class="form-label">Minimum Pembelian (Rp) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="min_pembelian" name="min_pembelian" value="<?php echo htmlspecialchars($min_pembelian); ?>" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" <?php echo ($status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php echo ($status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                <option value="Selesai" <?php echo ($status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Promosi</button>
                        <a href="promosi.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>