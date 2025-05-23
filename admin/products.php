<?php
require('../config.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: signin.php");
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

$message = '';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    if (!empty($image)) {
        $insert_product = mysqli_query($con, "INSERT INTO `products`(product_name, description, quantity, price, status, image) 
                                              VALUES('$product_name', '$description', '$quantity', '$price', 'Available', '$image')")
            or die(mysqli_error($con));

        if ($insert_product) {
            $_SESSION['message'] = 'Product added successfully!';
            header("Location: products.php");
            exit();
        } else {
            $message = 'Failed to add product!';
        }
    } else {
        $message = 'Please select an image!';
    }
}

// Fetch images from uploads folder
$upload_dir = '../uploads/';
$image_files = array_diff(scandir($upload_dir), array('..', '.'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        <h2 class="text-center">Manage Products</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
                . $_SESSION['message'] .
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
            unset($_SESSION['message']);
        }

        if (!empty($message)) {
            echo '<div class="alert alert-danger">' . $message . '</div>';
        }
        ?>

        <div class="container d-flex justify-content-center">
            <div class="card p-4 mb-3 border border-warning" style="max-width: 500px; width: 100%;">
                <div class="position-absolute top-0 end-0 m-3">
                    <a href="view_products.php" class="text-danger">
                        <img src="../uploads/menu.jpg" alt="Menu" width="30" height="30">
                    </a>
                </div>
                <h4 class="mb-4 text-center"><b>Add New Product</b></h4>
                <form action="products.php" method="post">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control"
                            placeholder="Enter product name" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                            placeholder="Enter product description (max 250 chars)" maxlength="250" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price (Rs.)</label>
                        <input type="number" id="price" name="price" step="0.01" class="form-control"
                            placeholder="Enter price" required>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control"
                            placeholder="Enter quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Select Image</label>
                        <select id="image" name="image" class="form-select" required>
                            <option value="">Choose an image...</option>
                            <?php foreach ($image_files as $file): ?>
                                <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" name="add_product" class="btn w-100">Add Product</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer text-center mt-4">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
</body>

</html>