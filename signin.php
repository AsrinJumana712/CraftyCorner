<?php
include("config.php");
session_start();

if (isset($_POST['signin'])) {
    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['id'] == 1 ? 'admin' : 'user';

        if ($_SESSION['role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        } else {
            header("Location: user/dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid username or password!";
    }
}
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
        .bg-image-login {
            background: url('./uploads/login_img.png') no-repeat center center;
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
                    <div class="col-lg-6 bg-image-login d-none d-lg-block"></div>
                    <div class="col-lg-6 p-5">
                        <h3 class="mb-3">Welcome Back!</h3>
                        <p class="text-muted">Login to continue exploring handcrafted products.</p>

                        <?php if (!empty($error)) {
                            echo "<div class='error-msg mb-3'>$error</div>";
                        } ?>

                        <div class="d-flex gap-2 mb-4 ">
                            <a href="#" class="btn social-btn"><i class="fab fa-google"></i> Google</a>
                            <a href="#" class="btn social-btn"><i class="fab fa-facebook-f"></i> Facebook</a>
                        </div>

                        <form method="post" action="">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger text-center py-2"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Username"
                                    required />
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required />
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="signup.php" class="text-decoration-none small">Forgot Password?</a>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms" required />
                                <label class="form-check-label" for="terms">Agree to terms & conditions</label>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" name="signin" class="btn w-100">Sign In</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">Don’t have an account?</span>
                            <a href="signup.php" class="btn-sm ms-2">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>