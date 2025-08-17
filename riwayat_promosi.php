<?php
include_once 'includes/db_connect.php';
include 'header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Riwayat Penggunaan Promosi Pelanggan</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Riwayat</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Promosi</th>
                            <th>Tanggal Digunakan</th>
                            <th>Jumlah Diskon Didapat</th>
                            <th>Aksi</th> 
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT
                                    rp.riwayat_id,
                                    p.nama AS nama_pelanggan,
                                    pr.nama_promosi,
                                    rp.tanggal_digunakan,
                                    rp.jumlah_diskon_didapat
                                FROM
                                    riwayat_promosi_pelanggan rp
                                JOIN
                                    pelanggan p ON rp.pelanggan_id = p.pelanggan_id
                                JOIN
                                    promosi pr ON rp.promosi_id = pr.promosi_id
                                ORDER BY
                                    rp.tanggal_digunakan DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["riwayat_id"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_pelanggan"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_promosi"]). "</td>";
                                echo "<td>" . htmlspecialchars($row["tanggal_digunakan"]). "</td>";
                                echo "<td>Rp " . number_format($row["jumlah_diskon_didapat"], 0, ',', '.') . "</td>";
                                echo "<td>";
                                // LINK EDIT RIWAYAT PROMOSI
                                echo "<a href='edit_riwayat_promosi.php?id=" . htmlspecialchars($row["riwayat_id"]) . "' class='btn btn-sm btn-info me-1'>Edit</a>";
                                // LINK HAPUS RIWAYAT PROMOSI
                                echo "<a href='hapus_riwayat_promosi.php?id=" . htmlspecialchars($row["riwayat_id"]) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus riwayat promosi ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }  else {
                            echo "<tr><td colspan='6'>Tidak ada riwayat penggunaan promosi.</td></tr>"; 
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