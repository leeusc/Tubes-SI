<?php
include '../../database/connect.php';

function calculateGrade($nilai) {
    if ($nilai >= 80) return 'A';
    if ($nilai >= 70) return 'B';
    if ($nilai >= 60) return 'C';
    if ($nilai >= 50) return 'D';
    return 'E';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['nilai']) || empty($_POST['nim']) || empty($_POST['kd_matkul'])) {
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($_POST['nim'] ?? ''));
        exit();
    }

    $keyData = require '../Mahasiswa/config.php';
    $key = base64_decode($keyData['key']);
    $cipher = "AES-256-CBC";
    $ivlen = openssl_cipher_iv_length($cipher);

    $nilai_numeric = $_POST['nilai'];
    $grade_letter = calculateGrade($nilai_numeric);

    // Encrypt nilai
    $iv_nilai = random_bytes($ivlen);
    $encrypted_nilai = openssl_encrypt($nilai_numeric, $cipher, $key, OPENSSL_RAW_DATA, $iv_nilai);
    $nilai_encrypted = $iv_nilai . $encrypted_nilai;

    // Encrypt grade
    $iv_grade = random_bytes($ivlen);
    $encrypted_grade = openssl_encrypt($grade_letter, $cipher, $key, OPENSSL_RAW_DATA, $iv_grade);
    $grade_encrypted = $iv_grade . $encrypted_grade;

    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];

    $query = "UPDATE nilai SET nilai = ?, grade = ? WHERE NIM = ? AND kd_matkul = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        $conn->close();
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
        exit();
    }

    $stmt->bind_param("ssss", $nilai_encrypted, $grade_encrypted, $nim, $kd_matkul);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
    exit();
} else {
    header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($_POST['nim'] ?? ''));
    exit();
}
?>
