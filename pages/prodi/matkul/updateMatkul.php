<?php
include __DIR__ . "/../../../database/connect.php";

// Get kd_matkul from URL
if (!isset($_GET['id'])) {
    die("Matkul ID not found.");
}

$kd_matkul = $_GET['id'];

// Fetch data matkul
$result = mysqli_query(
    $conn,
    "SELECT * FROM matkul WHERE kd_matkul='$kd_matkul'"
);
$matkul = mysqli_fetch_assoc($result);

if (!$matkul) {
    die("Mata kuliah tidak ditemukan.");
}

// UPDATE logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama_matkul = $_POST['nama_matkul'];
    $sks = $_POST['sks'];
    $kd_prodi = $matkul['kd_prodi']; // keep original prodi

    $query = "UPDATE matkul 
              SET nama_matkul='$nama_matkul', sks='$sks'
              WHERE kd_matkul='$kd_matkul'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?page=profile-prodi&id=$kd_prodi");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>


<form action="" method="POST">
    <h2>Edit Mata Kuliah</h2>

    <div>
        <label>Kode Mata Kuliah</label>
        <input type="text" value="<?= htmlspecialchars($matkul['kd_matkul']) ?>" disabled>
    </div>

    <div>
        <label>Nama Mata Kuliah</label>
        <input type="text" name="nama_matkul" required
            value="<?= htmlspecialchars($matkul['nama_matkul']) ?>">
    </div>

    <div>
        <label>SKS</label>
        <input type="number"
            name="sks"
            required
            min="1"
            max="6"
            value="<?= htmlspecialchars(trim((string)($matkul['sks'] ?? ''))) ?>">
    </div>
    <button type="submit">Update</button>
</form>