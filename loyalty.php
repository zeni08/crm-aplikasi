<?php
include_once 'includes/db_connect.php';
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Poin Loyalty Pelanggan</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Poin</th>
                            <th>Nama Pelanggan</th>
                            <th>Jumlah Poin</th>
                            <th>Terakhir Diperbarui</th>
                            </tr>
                    </thead>
                    <tbody>
                    <tbody>
                        <?php
                        $sql = "SELECT
                                    lp.poin_id,
                                    p.pelanggan_id, -- Tambahkan pelanggan_id di sini
                                    p.nama AS nama_pelanggan,
                                    lp.jumlah_poin,
                                    lp.tanggal_terakhir_update
                                FROM
                                    loyalty_poin lp
                                JOIN
                                    pelanggan p ON lp.pelanggan_id = p.pelanggan_id
                                ORDER BY
                                    lp.tanggal_terakhir_update DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["poin_id"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_pelanggan"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["jumlah_poin"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["tanggal_terakhir_update"]). "</td>";
                                // Tombol Aksi (Edit Poin)
                                echo "<td>";
                                echo "<a href='edit_loyalty.php?id=" . htmlspecialchars($row["poin_id"]) . "' class='btn btn-sm btn-warning'>Edit Poin</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data poin loyalty.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>