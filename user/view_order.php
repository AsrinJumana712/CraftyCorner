<?php
require('../config.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

// Retrieve user ID from session or database
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username='$username'";
$user_result = $con->query($sql_user);
$user = $user_result->fetch_assoc();

$order_id = $_GET['id'];

// Fetch order details
$sql_order = "SELECT o.order_id, o.order_date, o.status, o.total_amount, p.product_name 
              FROM orders o 
              JOIN products p ON o.product_id = p.id 
              WHERE o.order_id = $order_id AND o.user_id = (SELECT id FROM users WHERE username='$username')";
$order_result = $con->query($sql_order);
$order = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
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
        <h2 class="text-center mb-4">Order Details</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-white text-center rounded-top-4">
                        <h5 class="mb-0">Order ID: <?php echo $order['order_id']; ?></h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-3"><strong>Product:</strong> <?php echo $order['product_name']; ?></p>
                        <p class="mb-3"><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
                        <p class="mb-3"><strong>Status:</strong>
                            <span
                                class="badge bg-<?php echo ($order['status'] == 'Delivered') ? 'success' : 'warning'; ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </p>
                        <p class="mb-4"><strong>Total Amount:</strong> $<?php echo $order['total_amount']; ?></p>

                        <a href="order_history.php" class="btn w-100">Go back</a>
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