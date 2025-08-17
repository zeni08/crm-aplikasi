<?php
include_once 'includes/db_connect.php';
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
        <h3>Daftar Promosi</h3>
            <a href="tambah_promosi.php" class="btn btn-success mb-3">Tambah Promosi Baru</a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Promosi</th>
                            <th>Nama Promosi</th>
                            <th>Kode Promosi</th>
                            <th>Tipe Diskon</th>
                            <th>Nilai Diskon</th>
                            <th>Min. Pembelian</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM promosi ORDER BY tanggal_mulai DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["promosi_id"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_promosi"]). "</td>";
                                echo "<td>" . ($row["kode_promosi"] ? htmlspecialchars($row["kode_promosi"]) : '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row["tipe_diskon"]). "</td>";
                                echo "<td>" . ($row["tipe_diskon"] == 'persentase' ? htmlspecialchars($row["nilai_diskon"] * 100) . '%' : 'Rp ' . number_format($row["nilai_diskon"], 0, ',', '.')) . "</td>";
                                echo "<td>Rp " . number_format($row["min_pembelian"], 0, ',', '.') . "</td>";
                                echo "<td>" . htmlspecialchars($row["tanggal_mulai"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["tanggal_berakhir"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["status"]). "</td>";
                                echo "<td>";
                                // Tombol Aksi (Edit dan Hapus akan kita buat selanjutnya)
                                echo "<td>";
                                // LINK EDIT PROMOSI
                                echo "<a href='edit_promosi.php?id=" . htmlspecialchars($row["promosi_id"]) . "' class='btn btn-sm btn-info me-1'>Edit</a>";
                                // LINK HAPUS PROMOSI
                                echo "<a href='hapus_promosi.php?id=" . htmlspecialchars($row["promosi_id"]) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus promosi ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>Tidak ada data promosi.</td></tr>";
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