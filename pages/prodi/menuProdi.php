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
        <h3>Menu Prodi</h3>
    </header>

    <!-- Add new prodi -->
    <a href="index.php?page=insert-prodi" style="text-decoration: none; color: inherit;">
        <div class="insert-prodi">
            <span class="material-symbols-outlined">
                add
            </span>
            <h3>Tambah Prodi</h3>
        </div>
    </a>

    <div class="display-prodi">
        <span class="material-symbols-outlined">
            format_ink_highlighter
        </span>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="list-prodi">
                    <h5><?= htmlspecialchars($row['nama_prodi']) ?></h5>

                    <!-- Update button -->
                    <button class="prodi-update" onclick="location.href='index.php?page=update-prodi&id=<?= $row['kd_prodi'] ?>'">
                        Update
                    </button>

                    <!-- Delete form -->
                    <form action="delete-prodi.php" method="POST" style="display:inline;" 
                          onsubmit="return confirm('Hapus prodi <?= htmlspecialchars($row['nama_prodi']) ?>?')">
                        <input type="hidden" name="kd_prodi" value="<?= $row['kd_prodi'] ?>">
                        <button type="submit" class="prodi-delete">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada prodi.</p>
        <?php endif; ?>
    </div>

</main>
