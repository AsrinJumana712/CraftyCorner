<?php
require('../config.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

// Fetch all products
$result = mysqli_query($con, "SELECT * FROM `products`") or die(mysqli_error($con));

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
    <title>View Products</title>
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

    <div class="container mt-4">
        <h2 class="text-center mb-4">All Products</h2>
        <form method="GET" action="view_products.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for products..."
                    value="<?php echo ($search_query); ?>">
                <button type="submit" class="btn">Search</button>
            </div>
        </form>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                $image_path = "../uploads/" . htmlspecialchars($row['image']);
                $image_src = file_exists($image_path) ? $image_path : "../uploads/placeholder.png";
                ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card position-relative p-3 shadow-sm bg-white">
                        <span class="price-badge">Rs.<?php echo number_format($row['price'], 2); ?>/-</span>
                        <img src="<?php echo $image_src; ?>" class="product-image" alt="Product Image">
                        <h5 class="mt-3"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class="small text-muted"><?php echo htmlspecialchars($row['description'] ?? ''); ?></p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn">Update</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn"
                                onclick="return confirm('Are you sure?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="mt-1 text-center"><a href="products.php" class="btn w-15 ">Back to Products</a></div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>

</html>