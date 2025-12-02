<?php
include __DIR__ . "/../../../database/connect.php";

// Check if POST data exists
if (!isset($_POST['kd_matkul'])) {
    die("Kode Matkul tidak ditemukan.");
}

$kd_matkul = $_POST['kd_matkul'];

// Get kd_prodi to redirect later
$res = mysqli_query($conn, "SELECT kd_prodi FROM matkul WHERE kd_matkul='$kd_matkul'");
$mk = mysqli_fetch_assoc($res);

if (!$mk) {
    die("Mata kuliah tidak ditemukan.");
}

$kd_prodi = $mk['kd_prodi'];  // redirect back to prodi profile

// Delete matkul
$query = "DELETE FROM matkul WHERE kd_matkul='$kd_matkul'";

if (mysqli_query($conn, $query)) {
    header("Location: /Tugas-SI/index.php?page=profile-prodi&id=$kd_prodi");
    exit;
} else {
    echo "Gagal menghapus matakuliah: " . mysqli_error($conn);
}
?>
