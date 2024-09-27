<?php
require_once "../config.php";
session_start();

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    echo "Invalid request.";
}

$order_id = $_GET['order_id'];

// Fetch order details from the database
$sql = "SELECT * FROM `order` WHERE order_id = ?"; // Change 'id' to 'order_id'
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);

        // Generate receipt (you can format it as needed)
        echo "<h1>Receipt for Order ID: " . $order['order_id'] . "</h1>"; // Use 'order_id'
        echo "<p>Total Amount: $" . number_format($order['total_amount'], 2) . "</p>";
        echo "<p>Shipping Method: " . $order['shipping_method'] . "</p>";
        echo "<p>Shipping Time: " . $order['shipping_time'] . "</p>";
        echo "<p>Shipping Location: " . $order['shipping_location'] . "</p>";
        
        // Example of generating a simple downloadable text file
        $receipt_content = "Receipt for Order ID: " . $order['order_id'] . "\n"; // Use 'order_id'
        $receipt_content .= "Total Amount: $" . number_format($order['total_amount'], 2) . "\n";
        $receipt_content .= "Shipping Method: " . $order['shipping_method'] . "\n";
        $receipt_content .= "Shipping Time: " . $order['shipping_time'] . "\n";
        $receipt_content .= "Shipping Location: " . $order['shipping_location'] . "\n";

        // Set headers to download the receipt as a text file
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="receipt_' . $order['order_id'] . '.txt"');
        echo $receipt_content;

    } else {
        echo "Order not found.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
