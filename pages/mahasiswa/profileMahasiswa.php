<?php
$nim = $_GET['nim'] ?? '';

if (empty($nim)) {
    header("Location: index.php?page=menu-mahasiswa&error=missing_nim");
    exit();
}

include 'database/connect.php';

// Fetch mahasiswa data
$query = "SELECT m.*, p.nama_prodi FROM mahasiswa m 
              LEFT JOIN prodi p ON m.kd_prodi = p.kd_prodi 
              WHERE m.NIM = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();
$mahasiswa = $result->fetch_assoc();
$stmt->close();

if (!$mahasiswa) {
    $conn->close();
    header("Location: index.php?page=menu-mahasiswa&error=mahasiswa_not_found");
    exit();
}

// Get encryption key for decryption
$keyData = require 'database/Mahasiswa/config.php';
$key = base64_decode($keyData['key']);
$cipher = "AES-256-CBC";

// Decrypt nama
$encrypted_data = $mahasiswa['nama_mhs'];
$ivlen = openssl_cipher_iv_length($cipher);
$iv = substr($encrypted_data, 0, $ivlen);
$encrypted_nama = substr($encrypted_data, $ivlen);
$nama = openssl_decrypt($encrypted_nama, $cipher, $key, OPENSSL_RAW_DATA, $iv);

// Fetch nilai (grades) for this student
$queryNilai = "SELECT mk.kd_matkul, mk.nama_matkul, mk.SKS, 
                      n.nilai AS encrypted_nilai, n.grade AS encrypted_grade
               FROM matkul mk
               LEFT JOIN nilai n 
                   ON mk.kd_matkul = n.kd_matkul AND n.NIM = ?";

$stmtNilai = $conn->prepare($queryNilai);
$stmtNilai->bind_param("s", $nim);
$stmtNilai->execute();
$resultNilai = $stmtNilai->get_result();
$stmtNilai->close();
$conn->close();
?>

<main class="prof-mahasiswa">
    <header>
        <span class="material-symbols-outlined">
            diamond
        </span>
        <h3>profile mahasiswa</h3>
    </header>

    <div class="card">
        <div class="card-title">
            <div class="left-group">
                <h3>ITHB</h3>
                <div class="">
                    <span class="material-symbols-outlined">
                        assignment_ind
                    </span>
                    <h4>cardid</h4>
                </div>
            </div>
            <span class="material-symbols-outlined">
                more_horiz
            </span>
        </div>

        <div class="identity">

            <div class="field">
                <div class="label">
                    <span class="material-symbols-outlined">
                        badge
                    </span>
                    <span>NIM</span>
                </div>
                <div class="input"><?php echo htmlspecialchars($mahasiswa['NIM']); ?></div>
            </div>

            <div class="field">
                <div class="label">
                    <span class="material-symbols-outlined">
                        title
                    </span>
                    <span>Name</span>
                </div>
                <div class="input"><?php echo htmlspecialchars($nama); ?></div>

            </div>

            <div class="field">
                <div class="label">
                    <span class="material-symbols-outlined">
                        add_home_work
                    </span>
                    <span>Alamat</span>
                </div>
                <div class="input"><?php echo htmlspecialchars($mahasiswa['alamat']); ?></div>

            </div>

            <div class="field">
                <div class="label">
                    <span class="material-symbols-outlined">
                        crown
                    </span>
                    <span>Prodi</span>
                </div>
                <div class="input"><?php echo htmlspecialchars($mahasiswa['nama_prodi'] ?? 'N/A'); ?></div>
            </div>

        </div>
</main>


<main class="matkul">
    <header>
        <span class="material-symbols-outlined">
            subject
        </span>

        <h3>MataKuliah</h3>
    </header>
    <ul class="matkul-list">
        <?php while ($nilai = $resultNilai->fetch_assoc()): ?>
            <?php
            // Decrypt nilai
            if (!empty($nilai['encrypted_nilai'])) {
                $iv_nilai = substr($nilai['encrypted_nilai'], 0, $ivlen);
                $encrypted_nilai_data = substr($nilai['encrypted_nilai'], $ivlen);
                $nilai_decrypted = openssl_decrypt($encrypted_nilai_data, $cipher, $key, OPENSSL_RAW_DATA, $iv_nilai);
            } else {
                $nilai_decrypted = null;
            }

            // Decrypt grade
            if (!empty($nilai['encrypted_grade'])) {
                $iv_grade = substr($nilai['encrypted_grade'], 0, $ivlen);
                $encrypted_grade_data = substr($nilai['encrypted_grade'], $ivlen);
                $grade_decrypted = openssl_decrypt($encrypted_grade_data, $cipher, $key, OPENSSL_RAW_DATA, $iv_grade);
            } else {
                $grade_decrypted = null;
            }
            ?>

            <li>
                <span class="course-name">
                    <?= htmlspecialchars($nilai['nama_matkul']) ?> (<?= htmlspecialchars($nilai['SKS']) ?> SKS)
                </span>
                <ul class="scores">
                    <li>
                        Nilai: <?= htmlspecialchars($nilai_decrypted ?? 'Belum diinput') ?> |
                        Grade: <?= htmlspecialchars($grade_decrypted ?? 'Belum diinput') ?>

                        <?php if ($nilai_decrypted === null): ?>
                            <!-- Insert form -->
                            <form action="/Tugas-SI/database/Nilai/insertNilai.php" method="POST" class="form-nilai">
                                <input type="hidden" name="nim" value="<?= htmlspecialchars($nim) ?>">
                                <input type="hidden" name="kd_matkul" value="<?= htmlspecialchars($nilai['kd_matkul']) ?>">
                                <input type="number" name="nilai" min="0" max="100" placeholder="Nilai" required
                                    style="width:50px; padding:2px 4px; font-size:0.85rem;">
                                <button type="submit" style="padding:2px 6px; font-size:0.85rem;">+</button>
                            </form>
                        <?php else: ?>
                            <!-- Update/Delete links -->
                            <form action="/Tugas-SI/database/Nilai/updateNilai.php" method="POST" class="form-nilai" style="display:inline-flex;">
                                <input type="hidden" name="nim" value="<?= htmlspecialchars($nim) ?>">
                                <input type="hidden" name="kd_matkul" value="<?= htmlspecialchars($nilai['kd_matkul']) ?>">
                                <input type="number" name="nilai" min="0" max="100" value="<?= htmlspecialchars($nilai_decrypted) ?>"
                                    style="width:50px; padding:2px 4px; font-size:0.85rem;">
                                <button type="submit" style="padding:2px 6px; font-size:0.85rem;">Update</button>
                            </form>

                            <form action="/Tugas-SI/database/Nilai/deleteNilai.php" method="POST" class="form-nilai">
                                <input type="hidden" name="nim" value="<?= htmlspecialchars($nim) ?>">
                                <input type="hidden" name="kd_matkul" value="<?= htmlspecialchars($nilai['kd_matkul']) ?>">
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus nilai ini?');"
                                    style="padding:2px 6px; font-size:0.85rem;">Delete</button>
                            </form>

                        <?php endif; ?>
                    </li>
                </ul>
            </li>
            <hr>
        <?php endwhile; ?>
    </ul>

</main>