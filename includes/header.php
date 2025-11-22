<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "SIA School" ?></title>

    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/style2.css">
    <link rel="stylesheet" href="assets/form.css">
    <link rel="stylesheet" href="assets/style3.css">
    
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <script src="assets/script.js" defer></script>
</head>

<body>
<header>
    <button id="open-sidebar-button" onclick="openSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="40" height="40" fill="none" stroke="black"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="9" y1="3" x2="9" y2="21"></line>
        </svg>
    </button>

    <nav id="navbar">
        <ul>
            <li><button id="close-sidebar-button" onclick="closeSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="40" height="40" fill="none" stroke="black"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="3" x2="9" y2="21"></line>
                </svg>
            </button></li>

            <li class="home-li"><a class="active-link" href="index.php">Home</a></li>
            <li><a href="index.php?page=menu-prodi">Prodi</a></li>
            <li><a href="index.php?page=menu-mahasiswa">Mahasiswa</a></li>
            <li><a class="accent-link" href="index.php">SIA</a></li>
        </ul>
    </nav>
</header>

