<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$order_id = $_GET['id'];
$username = $_SESSION['username'];

// Fetch order details
$sql_order = "SELECT o.order_id, o.order_date, o.status, o.total_amount, p.product_name 
              FROM Orders o 
              JOIN Products p ON o.product_id = p.id 
              WHERE o.order_id = $order_id AND o.user_id = (SELECT id FROM Users WHERE username='$username')";
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
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order_history.php">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Order Details</h2>

    <div class="card">
        <div class="card-header">
            Order ID: <?php echo $order['order_id']; ?>
        </div>
        <div class="card-body">
            <p><strong>Product:</strong> <?php echo $order['product_name']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
            <p><strong>Total Amount:</strong> $<?php echo $order['total_amount']; ?></p>

            <a href="order_history.php" class="btn btn-custom">Go back</a>
        </div>
    </div>
</div>
<div class="footer">
      <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
</body>
</html>
