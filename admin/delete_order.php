<?php
require('../config.php');
session_start();

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Delete the order from the Orders table
    $sql = "DELETE FROM Orders WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: orders.php?msg=Order deleted successfully");
        exit();
    } else {
        echo "Error: " . $con->error;
    }
} else {
    echo "Invalid request.";
}
?>
