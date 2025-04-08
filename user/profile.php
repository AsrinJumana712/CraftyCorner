<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

// Fetch user details
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username='$username'";
$user_result = $con->query($sql_user);

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="order_history.php">Orders</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="send_feedback.php">Feedbacks</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ms-auto me-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        $nav_profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                        ?>
                        <img src="<?php echo $nav_profile_image; ?>" alt="Profile" class="rounded-circle"
                            style="height: 32px; width: 32px; object-fit: cover;"></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Your Profile</h4>
                        <?php
                        $profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                        ?>
                        <img src="<?php echo $profile_image; ?>" class="rounded-circle mb-3" alt="Profile Picture"
                            width="150" height="150">
                        <p><strong>Name:</strong> <?php echo($user['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo($user['email']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo($user['mob_no']); ?></p>
                        <p><strong>Home Address:</strong> <?php echo($user['home_address']); ?></p>
                        <a href="edit_profile.php" class="btn mt-3">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>

</html>