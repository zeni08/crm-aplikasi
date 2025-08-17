<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan - CRM App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
include_once 'includes/db_connect.php';
include 'header.php';

// Inisialisasi variabel pencarian
$search_query = $_GET['search'] ?? ''; // Ambil nilai pencarian dari URL
$search_sql = ''; // Untuk bagian WHERE clause

// Jika ada query pencarian, bangun bagian SQL WHERE
if (!empty($search_query)) {
    $search_sql = " WHERE nama LIKE '%" . $conn->real_escape_string($search_query) . "%' OR
                       email LIKE '%" . $conn->real_escape_string($search_query) . "%' OR
                       no_hp LIKE '%" . $conn->real_escape_string($search_query) . "%' OR
                       alamat LIKE '%" . $conn->real_escape_string($search_query) . "%'";
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Daftar Pelanggan</h3>

            <form class="d-flex mb-3" role="search" method="GET" action="pelanggan.php">
                <input class="form-control me-2" type="search" placeholder="Cari pelanggan..." aria-label="Search" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-success" type="submit">Cari</button>
                <?php if (!empty($search_query)): ?>
                    <a href="pelanggan.php" class="btn btn-outline-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>

            <a href="tambah_pelanggan.php" class="btn btn-success mb-3">Tambah Pelanggan Baru</a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Modifikasi query SQL untuk menyertakan kondisi pencarian
                        $sql = "SELECT pelanggan_id, nama, email, no_hp, alamat, tanggal_daftar FROM pelanggan" . $search_sql;
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["pelanggan_id"]. "</td>";
                                echo "<td>" . $row["nama"]. "</td>";
                                echo "<td>" . ($row["email"] ? $row["email"] : '-') . "</td>";
                                echo "<td>" . ($row["no_hp"] ? $row["no_hp"] : '-') . "</td>";
                                echo "<td>" . ($row["alamat"] ? $row["alamat"] : '-') . "</td>";
                                echo "<td>" . $row["tanggal_daftar"]. "</td>";
                                echo "<td>";
                                echo "<a href='edit_pelanggan.php?id=" . $row["pelanggan_id"] . "' class='btn btn-sm btn-info me-1'>Edit</a>";
                                echo "<a href='hapus_pelanggan.php?id=" . $row["pelanggan_id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pelanggan ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Tidak ada data pelanggan yang cocok dengan pencarian Anda.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>