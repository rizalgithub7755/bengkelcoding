<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - Sistem Informasi Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="#">
            Sistem Informasi Poliklinik
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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
                <div class="card-header">Registration</div>
                <div class="card-body">
                    <?php
                    include("koneksi.php"); // Include the database connection file

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST["username"];
                        $password = $_POST["password"];
                        $confirmPassword = $_POST["confirmPassword"];

                        // Check if the password and confirm password match
                        if ($password !== $confirmPassword) {
                            $registrationError = "Password and confirm password do not match.";
                        } else {
                            // Check if the username is already taken
                            $checkUsernameQuery = "SELECT * FROM user WHERE username = ?";
                            $stmt = $mysqli->prepare($checkUsernameQuery);
                            $stmt->bind_param("s", $username);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $registrationError = "Username is already taken.";
                            } else {
                                // Hash the password
                                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                                // Insert the user data into the database
                                $insertUserQuery = "INSERT INTO user (username, password) VALUES (?, ?)";
                                $stmt = $mysqli->prepare($insertUserQuery);
                                $stmt->bind_param("ss", $username, $hashedPassword);

                                if ($stmt->execute()) {
                                    $registrationSuccess = "Registration successful. You can now log in.";
                                } else {
                                    $registrationError = "Registration failed. Please try again.";
                                }
                            }
                        }
                    }
                    ?>
                    <form method="post" action="registrasiuser.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                    <?php if (isset($registrationSuccess)) { ?>
                        <p class="text-success"><?php echo $registrationSuccess; ?></p>
                    <?php } ?>
                    <?php if (isset($registrationError)) { ?>
                        <p class="text-danger"><?php echo $registrationError; ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="loginUser.php">Login here</a></p>
            </div>
        </div
