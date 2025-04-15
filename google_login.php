<?php
include("config.php");
session_start();

header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);

$email = isset($input['email']) ? $input['email'] : '';
$username = isset($input['username']) ? $input['username'] : '';
$ProfilePicture = isset($input['photoURL']) ? $input['photoURL'] : '';

if (!$email || !$username) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

if ($con->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}

// Check if user exists
$result = $con->query("SELECT * FROM users WHERE email = '$email'");
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['id'] == 1 ? 'admin' : 'user';

    // Update photo URL if it's not already set
    if (empty($user['ProfilePicture']) && $ProfilePicture !== '') {
        $update = $con->prepare("UPDATE users SET ProfilePicture = ? WHERE email = ?");
        $update->bind_param("ss", $ProfilePicture, $email);
        $update->execute();
    }

} else {
    // New user: Insert record with ProfilePicture
    $stmt = $con->prepare("INSERT INTO users (username, email, password, mob_no, home_address, ProfilePicture) VALUES (?, ?, '', '', '', ?)");
    $stmt->bind_param("sss", $username, $email, $ProfilePicture);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed"]);
        exit();
    }
}

// Redirect to appropriate dashboard
if ($_SESSION['role'] == 'admin') {
    echo json_encode(["status" => "success", "redirect" => "admin/dashboard.php"]);
} else {
    echo json_encode(["status" => "success", "redirect" => "user/dashboard.php"]);
}
?>
