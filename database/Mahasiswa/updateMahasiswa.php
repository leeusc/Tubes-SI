<?php
include '../../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['nama']) || empty($_POST['nim']) || empty($_POST['kd_prodi'])) {
        header("Location: ../../index.php?page=update-mahasiswa&nim=" . urlencode($_POST['nim'] ?? '') . "&error=missing_fields");
        exit();
    }

    $keyData = require 'config.php';
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

    $query = "UPDATE mahasiswa SET nama_mhs = ?, alamat = ?, kd_prodi = ? WHERE NIM = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        header("Location: ../../index.php?page=update-mahasiswa&nim=" . urlencode($nim) . "&error=prepare_failed");
        exit();
    }

    $stmt->bind_param("ssss", $nama_mhs, $alamat, $kd_prodi, $nim);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=menu-mahasiswa&success=updated");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=update-mahasiswa&nim=" . urlencode($nim) . "&error=update_failed");
        exit();
    }
} else {
    header("Location: ../../index.php?page=menu-mahasiswa");
    exit();
}
?>