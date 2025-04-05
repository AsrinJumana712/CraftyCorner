<?php
session_start();
require('../config.php');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Fetch user details including delivery address
$username = $_SESSION['username'];
$sql_user = "SELECT username, email, mob_no, home_address FROM Users WHERE username=?";
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
// echo "<pre>"; print_r($checkout_items); echo "</pre>";

// Calculate total price of selected items
$total_price = 0;
foreach ($checkout_items as &$item) { // Pass by reference to update session
    $item['quantity'] = (int) $item['quantity'];
    $item['price'] = (float) $item['price'];
    $total_price += $item['price'] * $item['quantity'];
}
unset($item); // Prevent accidental reference issues

// Dummy payment methods (Replace with DB query in real scenario)
$payment_methods = [
    ["type" => "Visa", "masked_number" => "**** **** **** ****"]
];

// Check if card details exist in session
$savedCard = $_SESSION['saved_card'] ?? null;

// If the saved card doesn't exist, redirect to add card page
if (!$savedCard) {
    header("Location: add_card.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Checkout</h2>

                        <!-- Delivery Address -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Delivery Address</h5>
                            <?php if (!empty($user['home_address'])): ?>
                                <p><strong><?= htmlspecialchars($user['username']) ?></strong></p>
                                <p><?= htmlspecialchars($user['mob_no']) ?></p>
                                <p><?= htmlspecialchars($user['home_address']) ?></p>
                            <?php else: ?>
                                <p class="text-danger">No delivery address found. Please update your profile.</p>
                            <?php endif; ?>
                            <a href="profile.php" class="btn btn-primary">Edit</a>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Payment Methods</h5>
                            <form>
                                <!-- Display the saved card -->
                                <?php if ($savedCard): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" checked>
                                        <label class="form-check-label">
                                            <?= htmlspecialchars($savedCard['type']) . " " . htmlspecialchars($savedCard['masked_number']); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- Option to add a new card -->
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment">
                                    <label class="form-check-label"><a href="add_card.php">Add a new card</a></label>
                                </div>
                            </form>
                        </div>


                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Order Summary</h5>
                            <ul class="list-group mb-3">
                                <?php foreach ($checkout_items as $item): ?>
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
                        <form action="order_success.php" method="POST">
                            <input type="hidden" name="order_items"
                                value="<?= htmlspecialchars(json_encode($checkout_items)) ?>">
                            <input type="hidden" name="total_price" value="<?= $total_price ?>">
                            <button type="submit" class="btn btn-danger w-100">Place Order</button>
                        </form>
                        <a href="cart.php" class="btn btn-secondary w-100 mt-2">Leave Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmOrder() {
            if (confirm('Are you sure you want to place this order?')) {
                document.getElementById('orderForm').submit();
            }
        }
    </script>
    <script src="../JavaScript/script.js"></script>
</body>

</html>