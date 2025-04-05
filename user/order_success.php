<?php
session_start();
require('../config.php');

// Clear the cart after order completion
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

        <div class="success-container">
            <div class="success-icon">✅</div>
            <h2 class="mt-3">Order Placed Successfully!</h2>
            <p class="text-muted">Thank you for your purchase. Your order will be processed soon.</p>
            <a href="dashboard.php" class="btn btn-continue">Continue Shopping</a>
        </div>

    <script>
        // Redirect to homepage after 5 seconds
        setTimeout(() => {
            window.location.href = "dashboard.php";
        }, 1000000);
    </script>

</body>
</html>
