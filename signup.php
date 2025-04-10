<?php
require('config.php');
session_start();

// Start output buffering
ob_start();

if (isset($_POST['signup'])) {
    $username = $con->real_escape_string($_POST['username']);
    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string($_POST['password']);
    $mob_no = $con->real_escape_string($_POST['mob_no']);

    $checkUser = "SELECT * FROM users WHERE username='$username'";
    $checkResult = $con->query($checkUser);

    if ($checkResult && $checkResult->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        $sql = "INSERT INTO users (username, password, email, mob_no) VALUES ('$username', '$password', '$email', '$mob_no')";
        if ($con->query($sql) === TRUE) {
            $success = "Registration successful! Redirecting to sign-in page...";
            header("Location: signin.php");
            exit;
        } else {
            $error = "Error: " . $con->error;
        }
    }
}

// End output buffering and send the buffer content to the browser
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftyCorner</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css" />
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .bg-image-signup {
            background: url('./uploads/signup_img.png') no-repeat center center;
            background-size: cover;
            border-top-left-radius: 1rem;
            border-bottom-left-radius: 1rem;
        }
    </style>
</head>

<body>
    <div class="container auth-container d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-lg-10 mx-auto">
                <div class="card flex-lg-row overflow-hidden">
                    <div class="col-lg-6 bg-image-signup d-none d-lg-block"></div>
                    <div class="col-lg-6 p-5">
                        <h3 class="mb-3">Create Your Account</h3>
                        <p class="text-muted">Join CraftyCorner to start exploring handcrafted products.</p>

                        <?php if (!empty($error))
                            echo "<div class='error-msg mb-3'>$error</div>"; ?>
                        <?php if (!empty($success))
                            echo "<div class='text-success mb-3'>$success</div>"; ?>

                        <form method="post" action="">
                            <div class="mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Username"
                                    required />
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    required />
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required />
                            </div>
                            <div class="mb-3">
                                <input type="text" name="mob_no" class="form-control" placeholder="Mobile Number"
                                    required />
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms" required />
                                <label class="form-check-label" for="terms">Agree to terms & conditions</label>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" name="signup" class="btn w-100">Sign Up</button>
                            </div>
                        </form><br>

                        <div class="d-flex justify-content-center gap-3 mt-2">
                            <a href="#" class="btn rounded-circle p-3"
                                style="width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; transition: all 0.3s ease-in-out;">
                                <i class="fab fa-google" style="font-size: 22px;"></i>
                            </a>
                            <a href="#" class="btn rounded-circle p-3"
                                style="width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; transition: all 0.3s ease-in-out;">
                                <i class="fab fa-facebook-f" style="font-size: 22px;"></i>
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <span class="text-muted">Already have an account?</span>
                            <a href="signin.php" class="btn-sm ms-2">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>