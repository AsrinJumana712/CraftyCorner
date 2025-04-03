<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$sql_user = "SELECT * FROM Users WHERE username='$username'";
$user_result = $con->query($sql_user);
$user = $user_result->fetch_assoc();

// Fetch all orders for the user with product images
$sql_orders = "SELECT o.order_id, o.order_date, o.status, o.total_amount, p.product_name, p.image
               FROM Orders o 
               JOIN Products p ON o.product_id = p.id 
               WHERE o.user_id = '".$user['id']."' 
               ORDER BY o.order_date DESC";
$orders_result = $con->query($sql_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
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
    <h2 class="text-center mb-5">Order History</h2>

    <div class="row">
        <?php if ($orders_result->num_rows > 0) {
            while ($order = $orders_result->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="../uploads/<?php echo $order['image']; ?>" alt="<?php echo $order['product_name']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $order['product_name']; ?></h5>
                            <p class="card-text">
                                <strong>Order ID:</strong> <?php echo $order['order_id']; ?><br>
                                <strong>Order Date:</strong> <?php echo $order['order_date']; ?><br>
                                <strong>Status:</strong> <?php echo $order['status']; ?><br>
                                <strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?>
                            </p>
                            <a href="view_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm">View</a>
                            <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } 
        } else { ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No orders found.</div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="footer">
      <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
</body>
</html>
