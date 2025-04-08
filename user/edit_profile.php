<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch user data
$sql_user = "SELECT * FROM Users WHERE username='$username'";
$user_result = $con->query($sql_user);
$user = $user_result->fetch_assoc();

if (isset($_POST['update'])) {
    $email = $con->real_escape_string($_POST['email']);
    $mob_no = $con->real_escape_string($_POST['mob_no']);
    $home_address = $con->real_escape_string($_POST['home_address']);
    $password = !empty($_POST['password']) ? $con->real_escape_string($_POST['password']) : $user['password'];

    $password_update = !empty($_POST['password']) ? ", password='$password'" : '';

    // Handle Image Upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture_update = ", ProfilePicture='$image_name'";
            } else {
                echo "<p class='text-danger text-center'>Error uploading the image.</p>";
                $profile_picture_update = "";
            }
        } else {
            echo "<p class='text-danger text-center'>Invalid file type. Only JPG, JPEG, PNG & GIF allowed.</p>";
            $profile_picture_update = "";
        }
    } else {
        $profile_picture_update = "";
    }

    // Update User Data
    $sql_update = "UPDATE Users SET email='$email', mob_no='$mob_no' , home_address='$home_address' $password_update $profile_picture_update WHERE username='$username'";

    if ($con->query($sql_update) === TRUE) {
        echo "<p class='text-success text-center'>Profile updated successfully!</p>";
        header("Location: profile.php");
        exit();
    } else {
        echo "<p class='text-danger text-center'>Error: " . $con->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 style="font-weight: bold;">Edit Details</h4>
                        <?php
                        $profile_image = !empty($user['ProfilePicture']) ? "../uploads/" . $user['ProfilePicture'] : "../uploads/default.png";
                        ?>
                        <img src="<?php echo $profile_image; ?>" class="rounded-circle mb-3" alt="Profile Picture"
                            width="150" height="150">

                        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo $user['email']; ?>" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="mob_no" class="form-label">Mobile Number</label>
                                <input type="text" name="mob_no" class="form-control"
                                    value="<?php echo $user['mob_no']; ?>" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="home_address" class="form-label">Home Address</label>
                                <input type="text" name="home_address" class="form-control"
                                    value="<?php echo $user['home_address']; ?>" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter new password if you want to change it">
                            </div>

                            <div class="mb-3 text-start">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control">
                            </div>

                            <button type="submit" name="update" class="btn">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer text-center mt-4">
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>

</body>
</html>