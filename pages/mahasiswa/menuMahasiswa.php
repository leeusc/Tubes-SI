<main class="menu-mahasiswa">

    <?php
    include 'database/connect.php';

    // Get encryption key for decryption
    $keyData = require 'database/Mahasiswa/config.php';
    $key = base64_decode($keyData['key']);
    $cipher = "AES-256-CBC";

    // Fetch mahasiswa data
    $query = "SELECT * FROM mahasiswa";
    $result = $conn->query($query);
    ?>

    <header><span class="material-symbols-outlined">
            id_card
        </span>
        <h3>Menu Mahasiswa</h3>
    </header>

    <a href="index.php?page=insert-mahasiswa" style="text-decoration: none; color: inherit;">

        <div class="insert-mahasiswa">
            <span class="material-symbols-outlined">
                add
            </span>
            <h3>Tambah Mahasiswa</h3>
        </div>
    </a>

    <div class="display-mahasiswa">
        <span class="material-symbols-outlined">
            format_ink_highlighter
        </span>

         <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            // Decrypt nama
            $encrypted_data = $row['nama_mhs'];
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = substr($encrypted_data, 0, $ivlen);
            $encrypted_nama = substr($encrypted_data, $ivlen);
            $nama = openssl_decrypt($encrypted_nama, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $nim = htmlspecialchars($row['NIM']);
            $alamat = htmlspecialchars($row['alamat']);
            $kd_prodi = htmlspecialchars($row['kd_prodi']);
            $queryProdi = "SELECT nama_prodi FROM prodi WHERE kd_prodi = ?";
            $stmtProdi = $conn->prepare($queryProdi);
            $stmtProdi->bind_param("s", $kd_prodi);
            $stmtProdi->execute();
            $resultProdi = $stmtProdi->get_result();
            $prodi = $resultProdi->fetch_assoc();
            ?>

            <a href="index.php?page=profile-mahasiswa&nim=<?= urlencode($row['NIM']) ?>" class="list-mahasiswa">
                <h5><?php echo htmlspecialchars($nama) ?></h5>
                <h5><?php echo htmlspecialchars($prodi['nama_prodi']) ?></h5>
                <button class="mahasiswa-update" onclick="event.preventDefault(); window.location.href='index.php?page=update-mahasiswa&nim=<?= urlencode($row['NIM']) ?>'">Edit</button>
                <form method="POST" action="database/Mahasiswa/deleteMahasiswa.php" style="flex-shrink: 0; margin: 0; padding: 0; width: auto; display: inline-block; background: none; border: none; box-shadow: none;" onsubmit="return confirm('Yakin ingin menghapus?')">
                    <input type="hidden" name="nim" value="<?= htmlspecialchars($row['NIM']) ?>">
                    <button type="submit" class="mahasiswa-delete" onclick="event.stopPropagation()">Hapus</button>
                </form>
            </a>
            
        <?php endwhile; ?>

    </div>
</main>