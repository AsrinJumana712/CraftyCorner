<?php
require('../config.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

// Fetch users
$usersQuery = "SELECT * FROM Users";
$usersResult = $con->query($usersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="message.php"><img src="../uploads/not.png" width="25"
                                height="25"></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">Manage Users</h1>

        <div class="row">
            <?php while ($user = $usersResult->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $user['username']; ?></h5>
                            <p class="card-text"><strong>Email:</strong> <?php echo $user['email']; ?></p>
                            <p class="card-text"><strong>Mobile Number:</strong> <?php echo $user['mob_no']; ?></p>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>

</html>