<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            Sistem Informasi Poliklinik
        </a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">
                        Home
                    </a>
                </li>
                <?php
                session_start();
                if (isset($_SESSION['username'])) {
                    // If the user is logged in, display "Data Master" and "Periksa" options like in periksa.php
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="dokter.php?page=dokter">Data Master</a>';
                    echo '</li>';
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="periksa.php?page=periksa">Periksa</a>';
                    echo '</li>';
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="obat.php">Obat</a>';
                    echo '</li>';
                    echo '<a class="nav-link" href="logout.php">Logout</a>';
                    echo '</li>';
                }
                ?>
            </ul>
            
            <?php
            if (!isset($_SESSION['username'])) {
                // If the user is not logged in, display "Register" and "Login" options to the top-right
                echo '<ul class="navbar-nav ms-auto">';
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="registrasiuser.php">Register</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="loginUser.php">Login</a>';
                echo '</li>';
                echo '</ul>';
            }
            ?>
        </div>
    </div>
</nav>

<main role="main" class="container">
    <?php
    if (isset($_GET['page'])) {
    ?>
    <h2><?php echo ucwords($_GET['page']) ?></h2>
    <?php
    include($_GET['page'] . ".php");
    } else {
        echo "Selamat Datang di Sistem Informasi Poliklinik";
    }
    ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
