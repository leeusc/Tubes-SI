<?php
include __DIR__ . "/../../database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kd_prodi'])) {
    $kd_prodi = $_POST['kd_prodi'];

    $stmt = $conn->prepare("DELETE FROM prodi WHERE kd_prodi = ?");
    $stmt->bind_param("s", $kd_prodi);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect back to menu-prodi page
        header("Location: index.php?page=menu-prodi");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
