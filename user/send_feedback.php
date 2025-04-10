<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

// Retrieve user ID from session or database
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username='$username'";
$user_result = $con->query($sql_user);
$user = $user_result->fetch_assoc();

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = trim($_POST['feedback']);
    if (!empty($feedback)) {
        // Insert feedback into the Feedbacks table
        $sql_insert = "INSERT INTO feedbacks (user_id, feedback, submitted_at) VALUES ((SELECT id FROM users WHERE username = ?), ?, NOW())";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("ss", $_SESSION['username'], $feedback);
        $stmt_insert->execute();

        // Redirect to the same page to show the new feedback
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch user feedbacks
$sql = "SELECT f.id, f.feedback, f.submitted_at, f.reply 
        FROM feedbacks f 
        WHERE f.user_id = (SELECT id FROM users WHERE username = ?) 
        ORDER BY f.submitted_at DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Feedback</title>
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

    <main class="container my-5">
        <h2 class="text-center mb-4">Your Feedback</h2>

        <div class="mb-4">
            <h4>Submit New Feedback</h4>
            <form method="post" action="">
                <div class="mb-3">
                    <textarea name="feedback" class="form-control" rows="4" placeholder="Write your feedback here..."
                        required></textarea>
                </div>
                <button type="submit" name="submit_feedback" class="btn">Submit Feedback</button>
            </form>
        </div>

        <!-- Display existing feedback -->
        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Your Feedback</h5>
                                <p class="card-text"><?= nl2br(($row['feedback'])) ?></p>
                                <p class="text-muted small">Submitted on
                                    <?= date('F j, Y \a\t g:i A', strtotime($row['submitted_at'])) ?></p>
                                <hr>
                                <h6 class="card-subtitle mb-2 text-success">Admin Reply</h6>
                                <?php if ($row['reply']): ?>
                                    <p class="card-text"><?= nl2br(($row['reply'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">No reply yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No feedback messages found.</p>
        <?php endif; ?>
    </main>

</body>
</html>