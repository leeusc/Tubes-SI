<?php
include '../../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['nama']) || empty($_POST['nim']) ||  empty($_POST['kd_prodi'])) {
        header("Location: ../../index.php?page=insert-mahasiswa&error=missing_fields");
        exit();
    }

    $keyData = require '../config.php';
    $key = base64_decode($keyData['key']);
    $cipher = "AES-256-CBC";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = random_bytes($ivlen);

    // Encrypt nama
    $encrypted_nama = openssl_encrypt($_POST['nama'], $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $nama_mhs = $iv . $encrypted_nama;

    $nim = $_POST['nim'];
    $alamat = $_POST['alamat'];
    $kd_prodi = $_POST['kd_prodi']; 

    $query = "INSERT INTO mahasiswa (NIM, nama_mhs, alamat, kd_prodi) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        header("Location: ../../index.php?page=insert-mahasiswa&error=prepare_failed");
        exit();
    }

    $stmt->bind_param("ssss", $nim, $nama_mhs, $alamat, $kd_prodi);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=menu-mahasiswa&success=inserted");
        exit();
    } else {
        $error_code = $stmt->errno;
        $stmt->close();
        $conn->close();
        
        // Check for duplicate entry error (MySQL error 1062)
        if ($error_code === 1062) {
            header("Location: ../../index.php?page=insert-mahasiswa&error=duplicate_nim");
        } else {
            header("Location: ../../index.php?page=insert-mahasiswa&error=insert_failed");
        }
        exit();
    }
} else {
    header("Location: ../../index.php?page=menu-mahasiswa");
    exit();
}
?>
