<?php
require('../config.php');
session_start();

// Fetch the product data for editing
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Retrieve product details
    $sql = "SELECT * FROM Products WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        header("Location: products.php");
        exit();
    }
} else {
    header("Location: products.php");
    exit();
}

// Handle form submission for updating product details
if (isset($_POST['update_product'])) {
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    // Ensure correct data types
    $price = floatval($price);
    $quantity = intval($quantity);
    $product_id = intval($product_id);

    // Update the product in the database
    $sql = "UPDATE Products SET product_name = ?, description = ?, price = ?, quantity = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssdii', $product_name, $description, $price, $quantity, $product_id);

    if ($stmt->execute()) {
        header("Location: view_products.php");
        exit();
    } else {
        echo "Error updating product: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="message.php"><img src="../uploads/not.png" width="25"
                                height="25"></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Edit Product</h2>
        <form action="edit_product.php?id=<?php echo $product_id; ?>" method="post" class="mt-4">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" id="product_name" name="product_name" class="form-control"
                    value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3"
                    required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (Rs.)</label>
                <input type="number" id="price" name="price" step="0.01" class="form-control"
                    value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control"
                    value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>
            <button type="submit" name="update_product" class="btn">Update Product</button>
            <a href="view_products.php" class="btn">Cancel</a>
        </form>
    </div>

    <div class="footer text-center mt-4">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>

</html>