<?php
require('../config.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

// Retrieve user details
$username = $_SESSION['username'];
$sql_user = "SELECT username, email, mob_no, home_address, ProfilePicture FROM Users WHERE username=?";
$stmt = $con->prepare($sql_user);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "<p class='text-danger text-center'>User details not found!</p>";
    exit;
}


// Fetch only selected checkout items
if (!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])) {
    echo "<p class='text-danger text-center'>No items selected for checkout!</p>";
    echo "<a href='cart.php' class='btn btn-secondary'>Go Back to Cart</a>";
    exit;
}

// Ensure session has latest values
$checkout_items = $_SESSION['checkout_items'];

// Debugging: Uncomment to check session values
$cart_items = $_SESSION['cart'] ?? [];
$total_price = array_reduce($cart_items, function ($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

// Calculate total price of selected items
$total_price = 0;
foreach ($checkout_items as &$item) { // Pass by reference to update session
    $item['quantity'] = (int) $item['quantity'];
    $item['price'] = (float) $item['price'];
    $total_price += $item['price'] * $item['quantity'];
}
unset($item);

$payment_methods = [
    ["type" => "Visa", "masked_number" => "**** **** **** 1234"]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="order_history.php">Orders</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="send_feedback.php">Feedbacks</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ms-auto me-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        $nav_profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                        ?>
                        <img src="<?php echo $nav_profile_image; ?>" alt="Profile" class="rounded-circle"
                            style="height: 32px; width: 32px; object-fit: cover;"></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Checkout</h2>

                        <!-- Delivery Address -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Delivery Address</h5>
                            <?php if (!empty($user['home_address'])): ?>
                                <p class="mb-0"><strong><?= htmlspecialchars($user['username']) ?></strong></p>
                                <p class="mb-0"><?= htmlspecialchars($user['mob_no']) ?></p>
                                <p><?= htmlspecialchars($user['home_address']) ?></p>
                            <?php else: ?>
                                <p class="text-danger">No delivery address found. Please update your profile.</p>
                            <?php endif; ?>
                            <a href="profile.php" class="btn btn-sm mt-2">Edit</a>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Payment Methods</h5>
                            <form>
                                <?php foreach ($payment_methods as $index => $method): ?>
                                    <div class="form-check">
                                        <input class="form-check-input custom-radio" type="radio" name="payment"
                                            <?= $index === 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label">
                                            <?= htmlspecialchars($method['type']) . " " . htmlspecialchars($method['masked_number']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <div class="form-check">
                                    <input class="form-check-input custom-radio" type="radio" name="payment">
                                    <label class="form-check-label">
                                        <a href="add_card.php">Add a new card</a>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Order Summary</h5>
                            <ul class="list-group mb-3">
                                <?php foreach ($cart_items as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><?= htmlspecialchars($item['product_name']) ?>
                                            (x<?= (int) $item['quantity'] ?>)</span>
                                        <span>LKR <?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="text-end fs-5 fw-bold">Total: LKR <?= number_format($total_price, 2) ?></p>
                        </div>

                        <!-- Place Order Button -->
                        <form action="order_success.php" method="POST" class="d-grid gap-2 col-6 mx-auto">
                            <input type="hidden" name="order_items"
                                value="<?= htmlspecialchars(json_encode($checkout_items)) ?>">
                            <input type="hidden" name="total_price" value="<?= $total_price ?>">
                            <button type="submit" class="btn btn-danger w-100">Place Order</button>
                            <a href="cart.php" class="btn btn-secondary w-100 mt-2">Leave Order</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer text-center mt-4">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

    <script src="../JavaScript/script.js"></script>

</body>
</html>