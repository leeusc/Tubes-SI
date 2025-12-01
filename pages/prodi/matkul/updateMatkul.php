
<form action="" method="POST">
    <h2>Edit MataKuliah</h2>

    <input type="hidden" name="kd_prodi" value="<?= htmlspecialchars($prodi['kd_prodi'] ?? '') ?>">

    <div>
        <label for="nama_prodi">Nama Mata Kuliah</label>
        <input type="text" id="nama_matkul" name="nama_matkul" required 
               value="<?= htmlspecialchars($prodi['nama_prodi'] ?? '') ?>">
    </div>

    <div>
        <label for="fakultas">SKS</label>
        <input type="text" id="sks" name="sks" required 
               value="<?= htmlspecialchars($prodi['fakultas'] ?? '') ?>">
    </div>

    <button type="submit">Update</button>
</form>
