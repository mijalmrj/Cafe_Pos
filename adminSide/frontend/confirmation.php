<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config.php';
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract order and order details
    $order = $data['order'];
    $orderDetails = $data['orderDetails'] ?? null;

    if($order == null){
        echo "Order is null";
    }

    // Insert into 'order' table
    $stmt = $link->prepare("INSERT INTO `order` (total_amount, shipping_method, shipping_time, shipping_location, order_status) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($link->error));
    }

    $stmt->bind_param("dssss", $order['totalAmount'], $order['shippingMethod'], $order['shippingTime'], $order['shippingLocation'], $order['orderStatus']);

    if ($stmt->execute()) {
        // Get the last inserted order ID
        $orderId = $stmt->insert_id;
        $stmtDetails = $link->prepare("INSERT INTO `order_detail` (order_id, order_type, size, quantity) VALUES (?, ?, ?, ?)");

        if ($stmtDetails === false) {
            die('Prepare failed for order details: ' . htmlspecialchars($link->error));
        }

        foreach ($orderDetails as $item) {
            $stmtDetails->bind_param("isss", $orderId, $item['name'], $item['size'], $item['no']);
            $stmtDetails->execute();
        }

        header("Location: index.php");

        exit();
    } else {

        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
} else {
    echo "Invalid request method.";
}
?>