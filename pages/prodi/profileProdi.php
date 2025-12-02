<?php

include __DIR__ . "/../../database/connect.php";

// ====== GET PRODI DATA ======
if (!isset($_GET['id'])) {
    die("Prodi ID tidak ditemukan.");
}

$kd_prodi = $_GET['id'];

// Ambil data Prodi
$queryProdi = "SELECT * FROM prodi WHERE kd_prodi = ?";
$stmt = $conn->prepare($queryProdi);
$stmt->bind_param("s", $kd_prodi);
$stmt->execute();
$prodiResult = $stmt->get_result();

if ($prodiResult->num_rows === 0) {
    die("Prodi tidak ditemukan.");
}

$prodi = $prodiResult->fetch_assoc();

// ====== GET MATA KULIAH LIST ======
$queryMatkul = "SELECT * FROM matkul WHERE kd_prodi = ?";
$stmt2 = $conn->prepare($queryMatkul);
$stmt2->bind_param("s", $kd_prodi);
$stmt2->execute();
$matkulResult = $stmt2->get_result();
?>

<main class="matkul-prodi">

    <!-- HEADER -->
    <header>
        <h2><?= htmlspecialchars($prodi['nama_prodi']) ?></h2>
        <span class="material-symbols-outlined">school</span>
    </header>

    <!-- BUTTON TAMBAH -->
    <button class="insertP" onclick="location.href='index.php?page=insert-matkul&prodi=<?= $kd_prodi ?>'">
        Tambah MataKuliah
    </button>

    <!-- MATAKULIAH LIST -->
    <div class="display-p">
        <h3>Mata Kuliah</h3>

        <?php if ($matkulResult->num_rows > 0): ?>
            <?php while ($mk = $matkulResult->fetch_assoc()): ?>
                <div class="list-p">

                    <!-- NAMA MATAKULIAH -->
                    <span><?= htmlspecialchars($mk['nama_matkul']) ?> (<?= $mk['SKS'] ?> SKS)</span>

                    <!-- UPDATE BTN -->
                    <button class="updateP"
                        onclick="location.href='index.php?page=update-matkul&id=<?= $mk['kd_matkul'] ?>'">
                        Update
                    </button>

                    <!-- DELETE BTN -->
                
                    <form method="POST" action="/Tugas-SI/pages/prodi/matkul/deleteMatkul.php" style="flex-shrink: 0; margin: 0; padding: 0; width: auto; display: inline-block; background: none; border: none; box-shadow: none;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        <input type="hidden" name="kd_matkul" value="<?= $mk['kd_matkul'] ?>">
                        <button type="submit" class="deleteP">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <p>Tidak ada Mata Kuliah.</p>
        <?php endif; ?>

    </div>
</main>
