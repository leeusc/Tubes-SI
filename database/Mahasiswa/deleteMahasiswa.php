<?php
include '../../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['nim'])) {
        header("Location: ../../index.php?page=menu-mahasiswa&error=missing_nim");
        exit();
    }

    $nim = $_POST['nim'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // First, delete all nilai records for this student
        $queryNilai = "DELETE FROM nilai WHERE NIM = ?";
        $stmtNilai = $conn->prepare($queryNilai);
        
        if (!$stmtNilai) {
            throw new Exception("Failed to prepare nilai delete statement");
        }
        
        $stmtNilai->bind_param("s", $nim);
        $stmtNilai->execute();
        $stmtNilai->close();

        // Then, delete the mahasiswa record
        $queryMahasiswa = "DELETE FROM mahasiswa WHERE NIM = ?";
        $stmtMahasiswa = $conn->prepare($queryMahasiswa);
        
        if (!$stmtMahasiswa) {
            throw new Exception("Failed to prepare mahasiswa delete statement");
        }

        $stmtMahasiswa->bind_param("s", $nim);
        $stmtMahasiswa->execute();
        $stmtMahasiswa->close();

        // Commit transaction
        $conn->commit();
        $conn->close();
        
        header("Location: ../../index.php?page=menu-mahasiswa&success=deleted");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $conn->close();
        
        header("Location: ../../index.php?page=menu-mahasiswa&error=delete_failed");
        exit();
    }
} else {
    // Reject non-POST requests
    header("Location: ../../index.php?page=menu-mahasiswa&error=invalid_request");
    exit();
}
?>