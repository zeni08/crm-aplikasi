<?php
include_once 'includes/db_connect.php';
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Daftar Feedback Pelanggan</h3>
            <a href="tambah_feedback.php" class="btn btn-success mb-3">Tambah Feedback Baru</a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Feedback</th>
                            <th>Pelanggan</th>
                            <th>ID Transaksi</th>
                            <th>Isi Feedback</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mengambil data feedback dengan JOIN ke tabel pelanggan dan transaksi
                        $sql = "SELECT 
                                    f.feedback_id, 
                                    p.nama AS nama_pelanggan, 
                                    f.transaksi_id, 
                                    f.isi_feedback, 
                                    f.rating, 
                                    f.tanggal, 
                                    f.status 
                                FROM 
                                    feedback f
                                JOIN 
                                    pelanggan p ON f.pelanggan_id = p.pelanggan_id
                                LEFT JOIN 
                                    transaksi t ON f.transaksi_id = t.transaksi_id
                                ORDER BY 
                                    f.tanggal DESC"; 
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["feedback_id"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_pelanggan"]). "</td>";
                                echo "<td>" . ($row["transaksi_id"] ? htmlspecialchars($row["transaksi_id"]) : '-') . "</td>"; // Tampilkan '-' jika transaksi_id NULL
                                echo "<td>" . htmlspecialchars(substr($row["isi_feedback"], 0, 50)) . (strlen($row["isi_feedback"]) > 50 ? '...' : '') . "</td>"; // Potong feedback jika terlalu panjang
                                echo "<td>" . ($row["rating"] ? htmlspecialchars($row["rating"]) . " Bintang" : '-') . "</td>"; // Tampilkan '-' jika rating NULL
                                echo "<td>" . htmlspecialchars($row["tanggal"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["status"]). "</td>";
                                echo "<td>";
                                // Tombol Aksi (Edit dan Hapus akan kita buat selanjutnya)
                                echo "<td>";
                                // LINK EDIT FEEDBACK
                                echo "<a href='edit_feedback.php?id=" . htmlspecialchars($row["feedback_id"]) . "' class='btn btn-sm btn-info me-1'>Edit</a>";
                                // LINK HAPUS FEEDBACK
                                echo "<a href='hapus_feedback.php?id=" . htmlspecialchars($row["feedback_id"]) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus feedback ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Tidak ada data feedback.</td></tr>";
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