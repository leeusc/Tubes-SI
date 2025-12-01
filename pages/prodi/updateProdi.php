<?php
include __DIR__ . "/../../database/connect.php";

$message = "";
$prodi = [];

// 1. Get current prodi data if kd_prodi is provided

if (isset($_GET['id'])) {
    $kd_prodi = $_GET['id'];
    $sql = "SELECT * FROM prodi WHERE kd_prodi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kd_prodi);
    $stmt->execute();
    $result = $stmt->get_result();
    $prodi = $result->fetch_assoc();
    $stmt->close();
}


// 2. Update data if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kd_prodi        = $_POST['kd_prodi'];
    $nama_prodi      = $_POST['nama_prodi'];
    $fakultas        = $_POST['fakultas'];
    $nama_ketua_prodi = $_POST['nama_ketua_prodi'];

    $sql = "UPDATE prodi SET nama_prodi = ?, fakultas = ?, nama_ketua_prodi = ? WHERE kd_prodi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nama_prodi, $fakultas, $nama_ketua_prodi, $kd_prodi);

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

<form action="" method="POST">
    <h2>Edit Prodi</h2>

    <input type="hidden" name="kd_prodi" value="<?= htmlspecialchars($prodi['kd_prodi'] ?? '') ?>">

    <div>
        <label for="nama_prodi">Nama Prodi</label>
        <input type="text" id="nama_prodi" name="nama_prodi" required 
               value="<?= htmlspecialchars($prodi['nama_prodi'] ?? '') ?>">
    </div>

    <div>
        <label for="fakultas">Fakultas</label>
        <input type="text" id="fakultas" name="fakultas" required 
               value="<?= htmlspecialchars($prodi['fakultas'] ?? '') ?>">
    </div>

    <div>
        <label for="nama_ketua_prodi">Nama Ketua Prodi</label>
        <input type="text" id="nama_ketua_prodi" name="nama_ketua_prodi" required 
               value="<?= htmlspecialchars($prodi['nama_ketua_prodi'] ?? '') ?>">
    </div>

    <button type="submit">Update</button>
</form>

<?php if ($message != ""): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
