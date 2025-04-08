<?php
require('../config.php');
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete associated orders first
    $delete_orders_sql = "DELETE FROM Orders WHERE product_id = ?";
    $stmt_orders = $con->prepare($delete_orders_sql);
    $stmt_orders->bind_param('i', $product_id);
    $stmt_orders->execute();

    // Now delete the product
    $delete_product_sql = "DELETE FROM Products WHERE id = ?";
    $stmt_product = $con->prepare($delete_product_sql);
    $stmt_product->bind_param('i', $product_id);

    if ($stmt_product->execute()) {
        echo "<script>
                alert('Product and associated orders deleted successfully.');
                window.location.href = 'products.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete the product.');
                window.location.href = 'products.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid product ID.');
            window.location.href = 'products.php';
          </script>";
}
?>
