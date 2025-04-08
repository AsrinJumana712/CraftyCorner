<?php
require('../config.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

// Fetch orders
$ordersQuery = "
    SELECT o.order_id, u.username AS customer_name, p.product_name, p.quantity, p.price, o.status, o.total_amount, p.image
    FROM Orders o
    JOIN Users u ON o.user_id = u.id
    JOIN Products p ON o.product_id = p.id
";
$ordersResult = $con->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
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
                    <li class="nav-item"><a class="nav-link" href="message.php"><img src="../uploads/not.png" width="25"
                                height="25"></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">View Orders</h1>

        <div class="row">
            <?php while ($order = $ordersResult->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="../uploads/<?php echo $order['image']; ?>" alt="<?php echo $order['product_name']; ?>"
                            class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $order['product_name']; ?></h5>
                            <p class="card-text">
                                <strong>Order ID:</strong> <?php echo $order['order_id']; ?><br>
                                <strong>Customer:</strong> <?php echo $order['customer_name']; ?><br>
                                <strong>Quantity:</strong> <?php echo $order['quantity']; ?><br>
                                <strong>Price:</strong> $<?php echo number_format($order['price'], 2); ?><br>
                                <strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?><br>
                                <strong>Status:</strong> <?php echo $order['status']; ?>
                            </p>
                            <a href="update_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm">Update</a>
                            <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm"
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