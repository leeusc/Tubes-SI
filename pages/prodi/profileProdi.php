<?php
include __DIR__ . "/../../database/connect.php";

// fetch all prodi
$result = $conn->query("SELECT * FROM prodi");
?>

<main class="menu-prodi">

    <header>
        <span class="material-symbols-outlined">
            school
        </span>
        <h3>Prodi Informatika</h3>
    </header>

    <a href="index.php?page=insert-prodi" style="text-decoration: none; color: inherit;">

        <div class="insert-prodi">
            <span class="material-symbols-outlined">
                add
            </span>
            <h3>Tambah MataKuliah</h3>
        </div>
    </a>

    <div class="display-prodi">
        <span class="material-symbols-outlined">
            format_ink_highlighter
        </span>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <a href="index.php?page=profile-prodi&id=<?= urlencode($row['NIM']) ?>" class="list-mahasiswa">
                    <h5><?php echo htmlspecialchars($nama) ?></h5>
                    <h5><?php echo htmlspecialchars($prodi['nama_prodi']) ?></h5>
                    <button class="mahasiswa-update" onclick="event.preventDefault(); window.location.href='index.php?page=update-mahasiswa&nim=<?= urlencode($row['NIM']) ?>'">Edit</button>
                    <form method="POST" action="database/Mahasiswa/deleteMahasiswa.php" style="flex-shrink: 0; margin: 0; padding: 0; width: auto; display: inline-block; background: none; border: none; box-shadow: none;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        <input type="hidden" name="nim" value="<?= htmlspecialchars($row['NIM']) ?>">
                        <button type="submit" class="mahasiswa-delete" onclick="event.stopPropagation()">Hapus</button>
                    </form>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada mata kuliah.</p>
        <?php endif; ?>
    </div>

</main>