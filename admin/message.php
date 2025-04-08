<?php
require('../config.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Base SQL
$sql = "SELECT f.id, f.feedback, f.submitted_at, u.username, f.reply 
        FROM Feedbacks f 
        JOIN users u ON f.user_id = u.id";

if (!empty($search_query)) {
    $sql .= " WHERE f.feedback LIKE ? OR u.username LIKE ?";
}

$sql .= " ORDER BY f.submitted_at DESC";
$stmt = $con->prepare($sql);

if (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("ss", $search_term, $search_term);
}

$stmt->execute();
$result = $stmt->get_result();

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply = trim($_POST['reply']);

    if (!empty($reply)) {
        $sql_reply = "UPDATE Feedbacks SET reply = ? WHERE id = ?";
        $stmt_reply = $con->prepare($sql_reply);
        $stmt_reply->bind_param("si", $reply, $feedback_id);
        $stmt_reply->execute();
        header("Location: message.php");
        exit();
    }
}

// Handle feedback deletion (admin can delete any feedback)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback'])) {
    $feedback_id = $_POST['feedback_id'];

    $sql_delete = "DELETE FROM Feedbacks WHERE id = ?";
    $stmt_delete = $con->prepare($sql_delete);
    $stmt_delete->bind_param("i", $feedback_id);
    $stmt_delete->execute();

    // Refresh the page after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
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

    <div class="container mt-4">
        <h4 class="text-center mb-4">User Feedback Messages</h4>

        <!-- Search -->
        <form method="get" action="" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search feedback..."
                    value="<?= ($search_query) ?>">
                <button class="btn" type="submit">Search</button>
            </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col position-relative">
                        <div class="card h-100 shadow-lg border-light">
                            <div class="card-body">
                                <!-- Delete Icon Button -->
                                <form method="POST" class="position-absolute top-0 end-0 m-3"
                                    onsubmit="return confirm('Delete this feedback?');">
                                    <input type="hidden" name="feedback_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_feedback" title="Delete Feedback"
                                        style="background: none; border: none;">
                                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="ms-2 text-danger">
                                            <img src="../uploads/bin.png" alt="" width="20" height="20">
                                        </a>
                                    </button>
                                </form>

                                <h5 class="card-title"><?= htmlspecialchars($row['id']) ?> -
                                    <?= htmlspecialchars($row['username']) ?>
                                </h5>
                                <p class="card-text"><?= nl2br(htmlspecialchars($row['feedback'])) ?></p>
                                <p class="text-muted small">Submitted on
                                    <?= date('F j, Y \a\t g:i A', strtotime($row['submitted_at'])) ?>
                                </p>
                                <hr>

                                <h6 class="card-subtitle mb-2 text-success">Reply</h6>
                                <?php if ($row['reply']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($row['reply'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">No reply sent yet.</p>
                                <?php endif; ?>

                                <!-- Reply Form -->
                                <form method="POST" class="mt-2" id="reply-form-<?= $row['id'] ?>"
                                    onsubmit="hideTextarea(<?= $row['id'] ?>)">
                                    <div class="mb-2" id="reply-container-<?= $row['id'] ?>" <?php echo ($row['reply'] ? 'style="display:none;"' : ''); ?>>
                                        <textarea class="form-control" name="reply" rows="3" placeholder="Write your reply..."
                                            required></textarea>
                                    </div>
                                    <input type="hidden" name="feedback_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="submit_reply" class="btn btn-lg">Send Reply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <p class="text-center text-muted">No feedback messages found.</p>
        <?php endif; ?>
    </div>

    <script src="../JavaScript/script.js"></script>
</body>

</html>