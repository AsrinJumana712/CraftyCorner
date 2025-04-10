<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

// Initialize cart and selected items if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['selected_items'])) {
    $_SESSION['selected_items'] = [];
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

// Retrieve user ID from session or database
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username='$username'";
$user_result = $con->query($sql_user);
$user = $user_result->fetch_assoc();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => 1
        ];
    }

    $_SESSION['selected_items'][$product_id] = true;
}

// Function to remove an item from the cart
function removeFromCart($product_id)
{
    unset($_SESSION['cart'][$product_id]);
    unset($_SESSION['selected_items'][$product_id]);
}

// Function to update cart quantities and selections
function updateCart($quantities, $selected_items)
{
    foreach ($quantities as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = (int) $quantity;
        } else {
            removeFromCart($product_id);
        }
    }

    $_SESSION['selected_items'] = array_fill_keys($selected_items, true);
}

// Function to clear the cart
function clearCart()
{
    $_SESSION['cart'] = [];
    $_SESSION['selected_items'] = [];
}

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        addToCart($_POST['product_id'], $_POST['product_name'], $_POST['price']);
        header("Location: cart.php");
        exit();
    } elseif (isset($_POST['update_cart'])) {
        updateCart($_POST['quantity'], $_POST['selected_items'] ?? []);
        header("Location: cart.php");
        exit();
    } elseif (isset($_POST['checkout_selected']) && !empty($_POST['selected_items'])) {
        if (isset($_POST['quantity'])) {
            updateCart($_POST['quantity'], $_POST['selected_items']);
        }

        $_SESSION['checkout_items'] = array_intersect_key($_SESSION['cart'], array_flip($_POST['selected_items']));
        header("Location: checkout_confirm.php");
        exit();
    } elseif (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        // Handle AJAX update from JS
        $product_id = $_POST['product_id'];
        $quantity = (int) $_POST['quantity'];
        if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
        exit();
    }
}

// Handle remove and clear actions
if (isset($_GET['remove'])) {
    removeFromCart($_GET['remove']);
    header("Location: cart.php");
    exit();
}

if (isset($_GET['clear'])) {
    clearCart();
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>
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

    <div class="container mt-4 d-flex">
        <div class="cart-container flex-grow-1">
            <?php if (!empty($_SESSION['cart'])) { ?>
                <form method="POST" action="cart.php" class="d-flex" id="cartForm">
                    <div class="cart-list w-75">
                        <div class="mb-3">
                            <input type="checkbox" id="select-all" class="form-check-input">
                            <label for="select-all" class="form-check-label">Select All Items</label>
                        </div>

                        <?php
                        $total_price = 0;
                        foreach ($_SESSION['cart'] as $product_id => $product) {
                            $sql_product = "SELECT * FROM products WHERE id='$product_id'";
                            $product_result = $con->query($sql_product);
                            if ($product_result->num_rows > 0) {
                                $product_details = $product_result->fetch_assoc();
                                $image_path = "../uploads/" . $product_details['image'];
                                if (!file_exists($image_path)) {
                                    $image_path = "../uploads/placeholder.png";
                                }
                                $available_quantity = $product_details['quantity'];

                                $subtotal = $product['price'] * $product['quantity'];
                                $total_price += $subtotal;
                                ?>
                                <div class="cart-item d-flex align-items-center">
                                    <input type="checkbox" name="selected_items[]" value="<?php echo $product_id; ?>"
                                        class="select-item me-2" <?php echo isset($_SESSION['selected_items'][$product_id]) ? 'checked' : ''; ?>>

                                    <img src="<?php echo $image_path; ?>" alt="<?php echo $product['product_name']; ?>"
                                        class="cart-item-image me-2">

                                    <div class="cart-item-details flex-grow-1">
                                        <h5><?php echo $product['product_name']; ?></h5>
                                        <p class="price">LKR <?php echo number_format($product['price'], 2); ?></p>
                                        <p class="price mb-1">LKR <?php echo number_format($product['price'], 2); ?></p>
                                        <p class="text-muted small">Available: <?php echo number_format($available_quantity); ?>
                                            item(s)</p>
                                    </div>

                                    <div class="cart-item-actions d-flex align-items-center">
                                        <div class="quantity-selector me-3">
                                            <button type="button" class="btn-quantity decrease"
                                                onclick="changeQuantity('<?php echo $product_id; ?>', -1)">−</button>
                                            <input type="text" name="quantity[<?php echo $product_id; ?>]"
                                                id="quantity_<?php echo $product_id; ?>" value="<?php echo $product['quantity']; ?>"
                                                min="1" max="<?php echo $available_quantity; ?>"
                                                data-max="<?php echo $available_quantity; ?>" class="quantity-input text-center"
                                                readonly>
                                            <button type="button" class="btn-quantity increase"
                                                onclick="changeQuantity('<?php echo $product_id; ?>', 1)">+</button>
                                        </div>
                                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="ms-2 text-danger">
                                            <img src="../uploads/bin.png" alt="" width="30" height="30">
                                        </a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <div class="mt-1 text-center">
                            <a href="dashboard.php" class="btn btn-continue">Continue Shopping</a>
                            <a href="cart.php?clear=true" class="btn btn-clear">Clear Cart</a>
                        </div>
                    </div>

                    <div class="checkout-info w-25 p-3 border-warning rounded ms-3 shadow-sm">
                        <h5>Checkout Summary</h5>
                        <div id="selected_products"></div>
                        <p><strong>Total Price: LKR <span
                                    id="selected_total"><?php echo number_format($total_price, 2); ?></span></strong></p>
                        <button type="submit" name="checkout_selected" class="btn w-100">Proceed to Checkout</button>
                    </div>
                </form>
            <?php } else { ?>
                <p class="text-center">Your cart is empty.</p>
                <div class="text-center">
                    <a href="dashboard.php" class="btn">Browse Products</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

    <script src="../JavaScript/script.js"></script>
</body>

</html>