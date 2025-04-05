<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Fetch user details from the database
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM Users WHERE username='$username'";
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
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-custom-header">Your Profile</div>
                        <div class="card-body card-body-custom text-center">
                            <?php
                            $profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                            ?>
                            <img src="<?php echo $profile_image; ?>" class="rounded-circle mb-3" alt="Profile Picture"
                                width="150" height="150">
                            <p><strong>Name:</strong> <?php echo $user['username']; ?></p>
                            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                            <p><strong>Contact Number:</strong> <?php echo $user['mob_no']; ?></p>
                            <p><strong>Home Address:</strong> <?php echo $user['home_address']; ?></p> <!-- Display Address -->
                            <a href="edit_profile.php" class="btn btn-danger">Edit Profile</a>
                        </div>

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
