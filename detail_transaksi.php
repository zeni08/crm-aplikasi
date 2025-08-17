
                <?php
include_once 'includes/db_connect.php'; // Ganti dari koneksi.php menjadi db_connect.php
include 'header.php'; // Include header.php yang baru dibuat
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detail Transaksi</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Periksa apakah ada parameter ID di URL
                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                        $transaksi_id = $conn->real_escape_string($_GET['id']); // Gunakan $conn, bukan $koneksi

                        // Query untuk mengambil detail transaksi, pelanggan
                        $sql_transaksi = "SELECT
                                            t.transaksi_id,
                                            t.tanggal,
                                            t.total,
                                            t.metode_pembayaran,
                                            t.status_transaksi,
                                            p.nama AS nama_pelanggan,
                                            p.email AS email_pelanggan,
                                            p.no_hp AS no_hp_pelanggan,
                                            p.alamat AS alamat_pelanggan
                                        FROM
                                            transaksi t
                                        JOIN
                                            pelanggan p ON t.pelanggan_id = p.pelanggan_id
                                        WHERE
                                            t.transaksi_id = '$transaksi_id'";

                        $result_transaksi = $conn->query($sql_transaksi); // Gunakan $conn

                        if ($result_transaksi->num_rows > 0) {
                            $data_transaksi = $result_transaksi->fetch_assoc();
                            ?>
                            <h5>Informasi Transaksi</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['transaksi_id']); ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Transaksi</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['tanggal']); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp <?php echo number_format($data_transaksi['total'], 2, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['metode_pembayaran']); ?></td>
                                </tr>
                                <tr>
                                    <th>Status Transaksi</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['status_transaksi']); ?></td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Informasi Pelanggan</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['nama_pelanggan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['email_pelanggan']); ?></td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['no_hp_pelanggan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?php echo htmlspecialchars($data_transaksi['alamat_pelanggan']); ?></td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Produk yang Dibeli</h5>
                            <?php
                            // Query untuk mengambil produk-produk dalam transaksi ini
                            $sql_detail_produk = "SELECT
                                                    dt.jumlah,
                                                    dt.harga_per_unit,
                                                    dt.subtotal,
                                                    pr.nama_produk
                                                FROM
                                                    detail_transaksi dt
                                                JOIN
                                                    produk pr ON dt.produk_id = pr.produk_id
                                                WHERE
                                                    dt.transaksi_id = '$transaksi_id'";

                            $result_detail_produk = $conn->query($sql_detail_produk); // Gunakan $conn

                            if ($result_detail_produk->num_rows > 0) {
                                ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga per Unit</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row_produk = $result_detail_produk->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row_produk['nama_produk']) . "</td>";
                                            echo "<td>Rp " . number_format($row_produk['harga_per_unit'], 2, ',', '.') . "</td>";
                                            echo "<td>" . htmlspecialchars($row_produk['jumlah']) . "</td>";
                                            echo "<td>Rp " . number_format($row_produk['subtotal'], 2, ',', '.') . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo "<p class='text-danger'>Tidak ada produk dalam transaksi ini. (Pastikan ada data di tabel detail_transaksi)</p>";
                            }
                            ?>

                            <div class="mt-4">
                                <a href="transaksi.php" class="btn btn-secondary">Kembali ke Daftar Transaksi</a>
                            </div>

                            <?php
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Transaksi dengan ID " . htmlspecialchars($transaksi_id) . " tidak ditemukan.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>ID Transaksi tidak ditemukan di URL.</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close(); // Tutup koneksi di sini
include 'footer.php'; // Include footer.php
?>