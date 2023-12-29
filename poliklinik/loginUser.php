<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Informasi Poliklinik</title>
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
            </ul>
        </div>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="registrasiuser.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="loginUser.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <?php
                    include("koneksi.php"); // Include the database connection file

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST["username"];
                        $password = $_POST["password"];

                        // Check if the username exists in the database
                        $checkUsernameQuery = "SELECT * FROM user WHERE username = ?";
                        $stmt = $mysqli->prepare($checkUsernameQuery);
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows === 1) {
                            $row = $result->fetch_assoc();

                            // Verify the password
                            if (password_verify($password, $row["password"])) {
                                // Login successful
                                session_start();
                                $_SESSION['username'] = $username;
                                header("Location: index.php");
                            } else {
                                $loginError = "Username atau Password salah.";
                            }
                        } else {
                            $loginError = "Username atau Password salah.";
                        }
                    }
                    ?>
                    <form method="post" action="loginUser.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </form>
                    <?php if (isset($loginError)) { ?>
                        <p class="text-danger"><?php echo $loginError; ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="mt-3 text-center">
                <p>Belum pernah mendaftar? <a href="registrasiuser.php">Daftar disini</a></p>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
