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