<?php
require('../config.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: auth.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
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

  <main class="container my-5">
    <h2 class="text-center mb-4">Welcome, Admin!</h2>
    <div class="row g-4">

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Manage Products</div>
          <div class="card-body">
            <p class="card-text">Add, edit, or delete products on the platform.</p>
            <a href="products.php" class="btn w-100">Go to Products</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">View Orders</div>
          <div class="card-body">
            <p class="card-text">Check customer orders and update their status.</p>
            <a href="orders.php" class="btn w-100">Go to Orders</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Manage Users</div>
          <div class="card-body">
            <p class="card-text">Manage user accounts and permissions.</p>
            <a href="users.php" class="btn w-100">Go to Users</a>
          </div>
        </div>
      </div>

    </div>

    <div class="footer text-center mt-5">
      <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
  </main>
</body>

</html>