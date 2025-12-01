<?php
include __DIR__ . "/../../database/connect.php";

$message = ""; // to show success/error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kd_prodi    = trim($_POST['kd_prodi']);
    $nama_prodi  = trim($_POST['nama_prodi']);
    $fakultas    = trim($_POST['fakultas']);
    $ketua_prodi = trim($_POST['nama_ketua_prodi']);

    $sql = "INSERT INTO prodi (kd_prodi, nama_prodi, fakultas, nama_ketua_prodi) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $kd_prodi, $nama_prodi, $fakultas, $ketua_prodi);

    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect to previous page or list page
        header("Location: index.php?page=menu-prodi");
        exit;
    } else {
        $message = "Error: " . $stmt->error;
    }

}
?>

<form action="" method="post">
    <h2>Tambah Prodi</h2>

    <div>
        <label for="kd_prodi">Kode Prodi</label>
        <input type="text" id="kd_prodi" name="kd_prodi" required>
    </div>

    <div>
        <label for="nama_prodi">Nama Prodi</label>
        <input type="text" id="nama_prodi" name="nama_prodi" required>
    </div>

    <div>
        <label for="fakultas">Fakultas</label>
        <input type="text" id="fakultas" name="fakultas" required>
    </div>

    <div>
        <label for="ketua_prodi">Ketua Prodi</label>
        <input type="text" id="nama_ketua_prodi" name="nama_ketua_prodi" required>
    </div>

    <button type="submit">Simpan</button>
</form>

<?php $conn->close(); ?>