<?php
session_start();
require('../config.php');

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle "Add to Cart" action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Check if product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => 1
        ];
    }

    header("Location: cart.php");
    exit();
}

// Handle "Remove from Cart"
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

// Handle "Clear Cart"
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

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

<div class="container mt-4">
    <h2 class="text-center">Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <div class="row g-4">
            <?php 
            $total_price = 0;
            foreach ($_SESSION['cart'] as $product_id => $product) {
                // Fetch product details from the database to get the image
                $sql_product = "SELECT * FROM products WHERE id='$product_id'";
                $product_result = $con->query($sql_product);
                if ($product_result->num_rows > 0) {
                    $product_details = $product_result->fetch_assoc();
                    $image_path = "../uploads/" . $product_details['image']; // Assuming 'image' column stores the image filename
                    if (!file_exists($image_path)) {
                        $image_path = "../uploads/placeholder.png"; // Placeholder if the image doesn't exist
                    }

                    // Define subtotal for each product
                    $subtotal = $product['price'] * $product['quantity'];
                    $total_price += $subtotal;
                } else {
                    // Handle case if product not found (optional)
                    $subtotal = 0;
                }
            ?>
            <div class="col-md-4">
                <div class="product-card">
                    <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                    <div class="cart-card-body">
                        <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                        <p class="card-text">Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p class="card-text">Quantity: <?php echo $product['quantity']; ?></p>
                        <p class="card-text">Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="btn btn-delete">Remove</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-end">
                <p class="total-price">Total: $<?php echo number_format($total_price, 2); ?></p>
            </div>
        </div>

        <div class="mt-1 text-center">
            <a href="dashboard.php" class="btn btn-continue">Continue Shopping</a>
            <a href="cart.php?clear=true" class="btn btn-clear">Clear Cart</a>
            <a href="checkout.php" class="btn btn-checkout">Checkout</a>
        </div>
    <?php } else { ?>
        <p class="text-center">Your cart is empty.</p>
        <div class="text-center">
            <a href="dashboard.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php } ?>
</div>
<div class="footer">
      <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
</body>
</html>
