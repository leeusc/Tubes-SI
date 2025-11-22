<?php
    $nim = $_GET['nim'] ?? '';
    include 'database/connect.php';
    
    // Fetch mahasiswa data based on NIM
    $query = "SELECT * FROM mahasiswa WHERE NIM = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    $mahasiswa = $result->fetch_assoc();
    $stmt->close();
    
    // Fetch all prodi for dropdown
    $queryProdi = "SELECT kd_prodi, nama_prodi FROM prodi ORDER BY nama_prodi";
    $resultProdi = $conn->query($queryProdi);
    
    // Get encryption key for decryption
    $keyData = require 'database/Mahasiswa/config.php';
    $key = base64_decode($keyData['key']);
    $cipher = "AES-256-CBC";
    
    // Decrypt nama
    $encrypted_data = $mahasiswa['nama_mhs'];
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($encrypted_data, 0, $ivlen);
    $encrypted_nama = substr($encrypted_data, $ivlen);
    $nama_decrypted = openssl_decrypt($encrypted_nama, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    
    $conn->close();
?>
<form action="database/Mahasiswa/updateMahasiswa.php" method="POST">
    <h2>Update Mahasiswa</h2>
    <input type="hidden" name="nim" value="<?php echo htmlspecialchars($nim); ?>">
    
    <div>
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required value="<?php echo htmlspecialchars($nama_decrypted); ?>">
    </div>

    <div>
        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" required value="<?php echo htmlspecialchars($mahasiswa['alamat']); ?>">
    </div>
    
    <div>
        <label for="kd_prodi">Program Studi</label>
        <select id="kd_prodi" name="kd_prodi" required>
            <option value="">-- Pilih Prodi --</option>
            <?php while ($prodi = $resultProdi->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($prodi['kd_prodi']); ?>" 
                    <?php echo ($prodi['kd_prodi'] === $mahasiswa['kd_prodi']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($prodi['nama_prodi']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit">Simpan</button>
</form>