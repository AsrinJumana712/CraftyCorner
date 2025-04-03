<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Fetch user details
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM Users WHERE username='$username'";
$user_result = $con->query($sql_user);

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

// Handle product search
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $con->real_escape_string($_GET['search']);
    $sql_products = "SELECT * FROM Products WHERE status='Available' AND (product_name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
} else {
    $sql_products = "SELECT * FROM Products WHERE status='Available'";
}

$result = $con->query($sql_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username; ?>'s Dashboard</title>
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
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Welcome, <?php echo $username; ?>!</h2>
    <p class="text-center">Browse and purchase our homemade products.</p>

    <!-- Search Form -->
    <form method="GET" action="dashboard.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="row">
        <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image_path = "../uploads/" . $row['image'];
                    if (!file_exists($image_path)) {
                        $image_path = "../uploads/placeholder.png";
                    }
        ?>
        <div class="col-md-4 mb-4">
            <div class="product-card position-relative">
                <span class="price-badge">Rs. <?php echo number_format($row['price'], 2); ?>/-</span>
                <img src="<?php echo $image_path; ?>" class="product-image" alt="<?php echo $row['product_name']; ?>">
                <h5 class="mt-3"><?php echo $row['product_name']; ?></h5>
                <p><?php echo htmlspecialchars($row['description'] ?? ''); ?></p>
                <form action="cart.php" method="post" class="d-inline">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                    <button type="submit" name="add_to_cart" class="btn btn-update w-100">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php 
                } 
            } else { 
        ?>
                <div class="col-12">
                    <p class="text-center text-danger">No products found.</p>
                </div>
        <?php } ?>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
</div>

</body>
</html>
