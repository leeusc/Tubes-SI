<?php
include '../../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['nim']) || empty($_POST['kd_matkul'])) {
        header("Location: ../../index.php?page=menu-nilai&error=missing_fields");
        exit();
    }

    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];

    // Delete nilai record
    $query = "DELETE FROM nilai WHERE NIM = ? AND kd_matkul = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        header("Location: ../../index.php?page=menu-nilai&error=delete_failed");
        exit();
    }

    $stmt->bind_param("ss", $nim, $kd_matkul);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=menu-nilai&success=deleted");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=menu-nilai&error=delete_failed");
        exit();
    }
} else {
    // Reject non-POST requests
    header("Location: ../../index.php?page=menu-nilai&error=invalid_request");
    exit();
}
?>