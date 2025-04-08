<?php
require('../config.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

//Fetch user details
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username='$username'";
$user_result = $con->query($sql_user);

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

// Fetch all products
$result = mysqli_query($con, "SELECT * FROM `products`") or die(mysqli_error($con));

// Handle product search
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $con->real_escape_string($_GET['search']);
    $sql_products = "SELECT * FROM products WHERE status='Available' AND (product_name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
} else {
    $sql_products = "SELECT * FROM products WHERE status='Available'";
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ms-auto me-4">
                <li class="nav-item"><a class="nav-link" href="message.php"><img src="../uploads/not.png" width="25"
                            height="25"></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <?php
                        $nav_profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                        ?>
                        <img src="<?php echo $nav_profile_image; ?>" alt="Profile" class="rounded-circle"
                            style="height: 32px; width: 32px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
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
        <div class="row row-cols-2 row-cols-lg-4 g-2 g-lg-3">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                $image_path = "../uploads/" . ($row['image']);
                $image_src = file_exists($image_path) ? $image_path : "../uploads/placeholder.png";
                ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card position-relative p-3 shadow-sm bg-white">
                        <span class="price-badge">Rs.<?php echo number_format($row['price'], 2); ?>/-</span>
                        <img src="<?php echo $image_src; ?>" class="product-image" alt="Product Image">
                        <h5 class="mt-3"><?php echo ($row['product_name']); ?></h5>
                        <p class="small text-muted"><?php echo ($row['description'] ?? ''); ?></p>
                        <p class="small text-muted">Available: <?php echo ($row['quantity'] ?? ''); ?></p>
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