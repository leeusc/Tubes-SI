<?php
// Display error messages
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo '<div class="error-message" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">';
    
    switch ($error) {
        case 'duplicate_nim':
            echo 'Error: NIM sudah terdaftar. Gunakan NIM yang berbeda.';
            break;
        case 'missing_fields':
            echo 'Error: Semua field harus diisi.';
            break;
        case 'insert_failed':
            echo 'Error: Gagal menambahkan mahasiswa. Silakan coba lagi.';
            break;
        default:
            echo 'Error: Terjadi kesalahan.';
    }
    
    echo '</div>';
}

// Fetch all prodi for dropdown
include 'database/connect.php';
$queryProdi = "SELECT kd_prodi, nama_prodi FROM prodi ORDER BY nama_prodi";
$resultProdi = $conn->query($queryProdi);
$conn->close();
?>

<form action="database/Mahasiswa/insertMahasiswa.php" method="POST">
    <h2>Tambah Mahasiswa</h2>
    <div>
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim" required>
    </div>
    
    <div>
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>
    </div>
    
    <div>
        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" required>
    </div>

    <div>
        <label for="kd_prodi">Program Studi</label>
        <select id="kd_prodi" name="kd_prodi" required>
            <option value="">-- Pilih Prodi --</option>
            <?php while ($prodi = $resultProdi->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($prodi['kd_prodi']); ?>">
                    <?php echo htmlspecialchars($prodi['nama_prodi']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit">Simpan</button>
</form>