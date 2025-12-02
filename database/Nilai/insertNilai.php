<?php
include '../../database/connect.php';

// Function to convert numeric score to letter grade
function calculateGrade($nilai) {
    if ($nilai >= 80) return 'A';
    if ($nilai >= 70) return 'B';
    if ($nilai >= 60) return 'C';
    if ($nilai >= 50) return 'D';
    return 'E';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (empty($_POST['nilai']) || empty($_POST['nim']) || empty($_POST['kd_matkul'])) {
        header("Location: ../../index.php?page=profile&nim=" . urlencode($_POST['nim']) . "&error=missing_fields");
        exit();
    }

    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];
    $nilai_numeric = $_POST['nilai'];

    // Load encryption key
    $keyData = require '../Mahasiswa/config.php';
    $key = base64_decode($keyData['key']);
    $cipher = "AES-256-CBC";
    $ivlen = openssl_cipher_iv_length($cipher); // 16 bytes for AES-256-CBC

    // Calculate grade
    $grade_letter = calculateGrade($nilai_numeric);

    // Check if nilai already exists
    $check = $conn->prepare("SELECT * FROM nilai WHERE NIM=? AND kd_matkul=?");
    $check->bind_param("ss", $nim, $kd_matkul);
    $check->execute();
    $resultCheck = $check->get_result();
    if ($resultCheck->num_rows > 0) {
        $check->close();
        $conn->close();
        header("Location: ../../index.php?page=profile&nim=" . urlencode($nim) . "&error=nilai_exists");
        exit();
    }
    $check->close();

    // Encrypt nilai
    $iv_nilai = random_bytes($ivlen); // must be exactly 16 bytes
    $enc_nilai = openssl_encrypt($nilai_numeric, $cipher, $key, OPENSSL_RAW_DATA, $iv_nilai);
    $nilai_encrypted = $iv_nilai . $enc_nilai;

    // Encrypt grade
    $iv_grade = random_bytes($ivlen);
    $enc_grade = openssl_encrypt($grade_letter, $cipher, $key, OPENSSL_RAW_DATA, $iv_grade);
    $grade_encrypted = $iv_grade . $enc_grade;

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO nilai (NIM, kd_matkul, nilai, grade) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $conn->close();
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
        exit();
    }

    $stmt->bind_param("ssss", $nim, $kd_matkul, $nilai_encrypted, $grade_encrypted);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
        exit();
    }

} else {
    // If not POST, redirect to profile
        header("Location: ../../index.php?page=profile-mahasiswa&nim=" . urlencode($nim));
    exit();
}
?>
