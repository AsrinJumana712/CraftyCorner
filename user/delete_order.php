<?php
require('../config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit;
}

// Get order ID from the query parameter
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Delete the order from the database
    $sql_delete = "DELETE FROM orders WHERE order_id = '$order_id'";

    if ($con->query($sql_delete)) {
        header("Location: order_history.php");
        exit;
    } else {
        echo "Error: " . $con->error;
    }
} else {
    header("Location: order_history.php");
    exit;
}
?>
