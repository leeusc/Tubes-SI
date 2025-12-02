<?php
// Connect
include __DIR__ . "/../../../database/connect.php";

// GET kd_prodi from URL
if (!isset($_GET['prodi'])) {
    die("Prodi ID not found in URL.");
}
$kd_prodi = $_GET['prodi'];

// === INSERT LOGIC ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $kd_matkul   = $_POST['kd_matkul'];
    $nama_matkul = $_POST['nama_matkul'];
    $sks         = $_POST['sks'];
    $kd_prodi    = $_POST['kd_prodi']; // hidden field

    $query = "INSERT INTO matkul (kd_matkul, nama_matkul, sks, kd_prodi)
              VALUES ('$kd_matkul', '$nama_matkul', '$sks', '$kd_prodi')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?page=profile-prodi&id=$kd_prodi");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// === FETCH PRODI NAME (optional for header display) ===
$result = mysqli_query($conn, "SELECT * FROM prodi WHERE kd_prodi='$kd_prodi'");
$prodi = mysqli_fetch_assoc($result);
?>

<form action="" method="post">
    <h2>Mata Kuliah</h2>

    <div>
        <label for="kd_matkul">Kode Mata Kuliah</label>
        <input type="text" id="kd_matkul" name="kd_matkul" required>
    </div>
    
    <div>
        <label for="nama_matkul">Nama Mata Kuliah</label>
        <input type="text" id="nama_matkul" name="nama_matkul" required>
    </div>

    <div>
        <label for="sks">SKS</label>
        <input type="number" id="sks" name="sks" required>
    </div>

    <!-- hidden kd_prodi taken from URL -->
    <input type="hidden" name="kd_prodi" value="<?= htmlspecialchars($_GET['prodi']) ?>">

    <button type="submit">Simpan</button>
</form>
