<?php 
    $title = "Home - SIA School";
    include "includes/header.php";
?>

<?php

$page = $_GET['page'] ?? 'home';

switch($page){
    case 'home': 
        include "pages/home.php";
        break;
    case 'menu-prodi': 
        include "pages/prodi/menuProdi.php";
        break; 
    case 'insert-prodi':
        include "pages/prodi/insertProdi.php";
        break;
    case 'update-prodi':
        include "pages/prodi/updateProdi.php";
        break;
    case 'profile-prodi';
        include "pages/prodi/profileProdi.php";
        break;
    case 'menu-mahasiswa':
        include "pages/mahasiswa/menuMahasiswa.php";
        break;
    case 'insert-mahasiswa':
        include "pages/mahasiswa/insertMahasiswa.php";
        break;
    case 'profile-mahasiswa':
        include "pages/mahasiswa/profileMahasiswa.php";
        break;
    case 'update-mahasiswa':
        include "pages/mahasiswa/updateMahasiswa.php";
        break;
    case 'insert-matkul';
        include "pages/prodi/matkul/insertMatkul.php";
        break;
    case 'update-matkul';
        include "pages/prodi/matkul/updateMatkul.php";
        break;              
}
?>

<?php include "includes/footer.php"; ?>