<?php
require('../config.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: signin.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = $_GET['id'];

try {
    // Start transaction
    $con->begin_transaction();

    // Delete all orders related to the user
    $delete_orders_query = "DELETE FROM orders WHERE user_id = ?";
    $stmt_orders = $con->prepare($delete_orders_query);
    $stmt_orders->bind_param('i', $user_id);
    $stmt_orders->execute();

    // Delete the user
    $delete_user_query = "DELETE FROM users WHERE id = ?";
    $stmt_user = $con->prepare($delete_user_query);
    $stmt_user->bind_param('i', $user_id);
    $stmt_user->execute();

    // Commit transaction
    $con->commit();
    echo "<script>alert('User and related orders deleted successfully!'); window.location.href = 'users.php';</script>";
} catch (mysqli_sql_exception $exception) {
    // Rollback transaction if any error occurs
    $con->rollback();
    echo "<script>alert('Failed to delete user: {$exception->getMessage()}'); window.location.href = 'users.php';</script>";
}
?>
