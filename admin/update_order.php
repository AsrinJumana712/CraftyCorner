<?php
require('../config.php');
session_start();

// Check if admin is logged in
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

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch current order details
    $sql = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Order not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
if (isset($_POST['update_order'])) {
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        header("Location: orders.php?msg=Order updated successfully");
        exit();
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
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
        <h2 class="text-center">Update Order Status</h2>
        <div class="card p-3 mb-4">
            <h4>Order ID: <?php echo $row['order_id']; ?></h4>
            <form action="update_order.php?id=<?php echo $row['order_id']; ?>" method="post">
                <div class="mb-3">
                    <label for="status" class="form-label">Order Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending" <?php if ($row['status'] == 'Pending')
                            echo 'selected'; ?>>Pending
                        </option>
                        <option value="Shipped" <?php if ($row['status'] == 'Shipped')
                            echo 'selected'; ?>>Shipped
                        </option>
                        <option value="Delivered" <?php if ($row['status'] == 'Delivered')
                            echo 'selected'; ?>>Delivered
                        </option>
                        <option value="Cancelled" <?php if ($row['status'] == 'Cancelled')
                            echo 'selected'; ?>>Cancelled
                        </option>
                    </select>
                </div>
                <button type="submit" name="update_order" class="btn">Update Order</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>

</html>