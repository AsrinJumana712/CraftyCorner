<?php
session_start();
require('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_number = $_POST['card_number'] ?? '';
    $cardholder_name = $_POST['cardholder_name'] ?? '';
    $expiry_month = $_POST['expiry_month'] ?? '';
    $expiry_year = $_POST['expiry_year'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $save_card = isset($_POST['save_card']) ? true : false;

    // Basic validation
    if (!empty($card_number) && !empty($cardholder_name) && !empty($expiry_month) && !empty($expiry_year) && !empty($cvv)) {
        // Mask card number except last 4 digits
        $masked_card = substr($card_number, 0, 4) . " **** **** " . substr($card_number, -4);

        // Store card in session (check if 'saved_cards' already exists)
        if (!isset($_SESSION['saved_cards'])) {
            $_SESSION['saved_cards'] = [];
        }
        
        $_SESSION['saved_cards'][] = [
            "type" => "Visa/MasterCard",
            "masked_number" => $masked_card,
            "cardholder_name" => $cardholder_name,
            "expiry" => "$expiry_month/$expiry_year"
        ];

        // Redirect to checkout confirmation page
        header("Location: checkout_confirm.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Card</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <script src="../bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .card-container {
            max-width: 450px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-save {
            background-color: #ff3b47;
            color: white;
            font-size: 1.1rem;
            border-radius: 25px;
        }
        .btn-save:hover {
            background-color: #e02a3b;
        }
        .card-icons img {
            width: 40px;
            margin-right: 5px;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Crafty<span class="header_name">Corner</span> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <div class="card-container">
            <h4 class="text-center fw-bold">Provide further information</h4>
            <p class="text-center text-success">✔ Your payment information is safe with us</p>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <!-- Card Icons -->
            <div class="d-flex align-items-center bg-light p-2 rounded">
                <span class="fw-bold">💳 Add a new card</span>
                <div class="ms-auto card-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="MasterCard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/American_Express_logo_%282018%29.svg" alt="AMEX">
                </div>
            </div>

            <!-- Card Form -->
            <form action="" method="POST">
                <div class="mt-3">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456" required>
                </div>

                <div class="mt-3">
                    <label class="form-label">Cardholder Name</label>
                    <input type="text" class="form-control" name="cardholder_name" value="A.R. Asrin Jumana" required>
                </div>

                <div class="row mt-3">
                    <div class="col-6">
                        <label class="form-label">Expiry Date</label>
                        <div class="d-flex">
                            <select class="form-select me-1" name="expiry_month" required>
                                <option value="" disabled selected>MM</option>
                                <?php for ($m = 1; $m <= 12; $m++) echo "<option>$m</option>"; ?>
                            </select>
                            <select class="form-select" name="expiry_year" required>
                                <option value="" disabled selected>YY</option>
                                <?php for ($y = date('Y'); $y <= date('Y') + 10; $y++) echo "<option>" . substr($y, -2) . "</option>"; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">CVV</label>
                        <input type="password" class="form-control" name="cvv" placeholder="•••" required>
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="save_card" checked>
                    <label class="form-check-label">Save card details</label>
                </div>

                <p class="text-muted small mt-1">ℹ Your order will be processed in USD</p>

                <button type="submit" class="btn btn-save w-100 mt-3">Save & Confirm</button>
            </form>
        </div>
    </div>

</body>
</html>
