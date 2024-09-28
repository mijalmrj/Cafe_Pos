<?php
require_once "../../config.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['order_id'])) {
    echo json_encode(['error' => 'No order ID provided']);
    exit;
}

$orderId = intval($_GET['order_id']);
$query = "
    SELECT 
        o.order_id, 
        o.user_id, 
        o.shipping_time, 
        o.total_amount, 
        od.product_name, 
        od.size,
        p.price
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    JOIN `products` p ON od.product_id = p.product_id
    WHERE o.order_id = ?
";

$stmt = $link->prepare($query);

if (!$stmt) {
    // Provide detailed error information
    echo json_encode(['error' => 'Database query preparation failed: ' . $link->error]);
    exit;
}

$stmt->bind_param("i", $orderId);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Database query execution failed: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$orderDetails = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($orderDetails);

$stmt->close();
?>
