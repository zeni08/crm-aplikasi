<?php
// includes/db_connect.php

$servername = "localhost"; // Ganti jika host database Anda berbeda
$username = "root";      // Ganti dengan username database Anda
$password = "";          // Ganti dengan password database Anda
$dbname = "crm";         // Nama database yang sesuai dengan crm.sql

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Opsional: Set character set ke utf8mb4
$conn->set_charset("utf8mb4");

//echo "Koneksi database berhasil!"; // Anda bisa mengaktifkan ini untuk tes awal

?>