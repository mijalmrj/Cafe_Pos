<?php
// Database connection details
$hostname = "localhost";
$username = "root";
$password = ""; 
$dbname = "Cafe"; 

// Create connection
$link = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Get order ID from the query string
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Fetch order details from the database
$sql = "SELECT * FROM `order` WHERE `order_id` = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the order exists
if ($result->num_rows === 0) {
    $order = null;
} else {
    $order = $result->fetch_assoc();
}

// Close statement and connection
$stmt->close();
$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="gradient-background">
    <div class="container">
        <div class="order-confirmation">
        <?php if ($order === null): ?>
            <h1>Order not found</h1>
            <p>We could not find the order you are looking for.</p>
        <?php else: ?>
            <h1>Order Confirmation</h1>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($order['total_amount']); ?></p>
            <p><strong>Shipping Method:</strong> <?php echo htmlspecialchars($order['shipping_method']); ?></p>
            <p><strong>Shipping Time:</strong> <?php echo htmlspecialchars($order['shipping_time']); ?></p>
            <p><strong>Shipping Location:</strong> <?php echo htmlspecialchars($order['shipping_location']); ?></p>
            <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>
