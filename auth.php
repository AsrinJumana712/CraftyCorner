<?php
require('config.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftyCorner</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Crafty Corner</h1>
        <div class="row justify-content-center g-4">

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                    placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password" required>
                            </div>
                            <button type="submit" name="signin" class="btn btn-primary w-100">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                    placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Create a password" required>
                            </div>
                            <div class="mb-3">
                                <label for="mob_no" class="form-label">Mobile Number</label>
                                <input type="text" name="mob_no" class="form-control"
                                    placeholder="Enter your mobile number" required>
                            </div>
                            <button type="submit" name="signup" class="btn btn-success w-100">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

    <?php
    if (isset($_POST['signin'])) {
        $username = $con->real_escape_string($_POST['username']);
        $password = $con->real_escape_string($_POST['password']);

        // Query to check user credentials
        $sql = "SELECT * FROM Users WHERE username='$username' AND password='$password'";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Store user information in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['id'] == 1 ? 'admin' : 'user';

            // Redirect based on role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin/dashboard.php");
                exit();
            } else {
                header("Location: user/dashboard.php");
                exit();
            }
        } else {
            echo "<p class='text-danger text-center'>Invalid username or password!</p>";
        }
    }

    if (isset($_POST['signup'])) {
        $username = $con->real_escape_string($_POST['username']);
        $email = $con->real_escape_string($_POST['email']);
        $password = $con->real_escape_string($_POST['password']);
        $mob_no = $con->real_escape_string($_POST['mob_no']);

        // Check if username already exists
        $checkUser = "SELECT * FROM Users WHERE username='$username'";
        $checkResult = $con->query($checkUser);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo "<p class='text-danger text-center'>Username already exists!</p>";
        } else {
            // Insert new user
            $sql = "INSERT INTO Users (username, password, email, mob_no) VALUES ('$username', '$password', '$email', '$mob_no')";
            if ($con->query($sql) === TRUE) {
                echo "<p class='text-success text-center'>Registration Successful!</p>";
            } else {
                echo "<p class='text-danger text-center'>Error: " . $con->error . "</p>";
            }
        }
    }
    ?>
</body>

</html>