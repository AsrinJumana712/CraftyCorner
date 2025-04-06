<?php
session_start();
require('../config.php');

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch the logged-in user's information
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM Users WHERE username='$username'";
$user_result = $con->query($sql_user);

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    header("Location: cart.php");
    exit();
}

// Process checkout
$user_id = $user['id'];
$total_amount = 0;

// Iterate over the cart and save each item in the Orders table
foreach ($_SESSION['cart'] as $product_id => $product) {
    $quantity = $product['quantity'];
    $subtotal = $product['price'] * $quantity;
    $total_amount += $subtotal;

    // Insert the order into the Orders table
    $sql_order = "INSERT INTO Orders (user_id, product_id, quantity, total_amount, status, order_date)
                  VALUES ('$user_id', '$product_id', '$quantity', '$subtotal', 'Pending', NOW())";
    if (!$con->query($sql_order)) {
        header("Location: checkout.php");
        exit();
    }
}

// Clear the cart after successful order placement
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span></a>
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

<div class="success-container">
    <div class="success-icon">✅</div>
    <h2 class="mt-3">Order Placed Successfully!</h2>
    <p class="text-muted">Thank you for your purchase. Your order will be processed soon.</p>

    <a href="dashboard.php" class="btn btn-success btn-continue">Continue Shopping</a>
</div>

</body>
</html>
