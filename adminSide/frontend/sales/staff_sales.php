<?php
require_once "../../config.php";
session_start();

$userId = $_SESSION['logged_user_id'];

// Prepare SQL query
$query = "
    SELECT o.order_id, o.user_id, o.shipping_time, o.total_amount, od.product_id ,od.product_name, od.size
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    WHERE o.user_id = ?
";
$stmt = $link->prepare($query);
if (!$stmt) {
    die("Database query preparation failed: " . $link->error);
}

// Bind parameters
$stmt->bind_param("i", $userId);

// Execute the statement
if (!$stmt->execute()) {
    die("Database query execution failed: " . $stmt->error);
}

// Get the result
$result = $stmt->get_result();
if (!$result) {
    die("Failed to retrieve results: " . $stmt->error);
}

// Group orders by order_id
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'shipping_time' => $row['shipping_time'],
            'total_amount' => $row['total_amount'],
            'user_id' => $row['user_id'],
            'items' => []
        ];
    }
    // Add product details to the items array
    $orders[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'size' => $row['size'],
    ];
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Transactions (Admin)</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="manage-body">
    <div class="container">
        <header class="navbar">
            <div class="navhome">
                <a href="index.html"><img src="../logos/home.png" id="coffee-logo"></a>
                <a href="index.html">
                    <h2>Northside Café</h2>
                </a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="../settings.html"><img src="../logos/settings.png"></a>
                    <a href="../report.html"><img src="../logos/analytics.png"></a>
                </div>
                <div class="generallinks">
                    <a href="../transactions_admin.html"><img src="../logos/print.png"></a>
                    <a href="../user.html"><img src="../logos/user.png"></a>
                </div>
            </div>
        </header>

        <div class="main-manage">
            <div class="manage-header">
                <h1>SALES TRANSACTIONS</h1>
            </div>
            <hr>
            <div class="manage-table">
                <table id="transaction-table">
                    <tr>
                        <th>DATE</th>
                        <th>ORDER</th>
                        <th>PRICE</th>
                        <th>STAFF</th>
                        <th>PRINT RECEIPT</th>
                        <th></th>
                    </tr>
                    <?php
                    // Check if there are results and output them
                    if (count($orders) > 0) {
                        foreach ($orders as $orderId => $order) {
                            // Display order details in a single row
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($order['shipping_time']) . "</td>";
                            echo "<td>";
                            foreach ($order['items'] as $item) {
                                echo htmlspecialchars($item['product_name']) . " (Size: " . htmlspecialchars($item['size']) . ")<br>";
                            }
                            echo "</td>";
                            echo "<td>$" . htmlspecialchars($order['total_amount']) . "</td>";
                            echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
                            echo "<td><button onclick='downloadReceipt(" . htmlspecialchars($orderId) . ")'>Print</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No transactions found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/transactions.js"></script>
    <script src="../js/downloadReceipt.js"></script>
</body>

</html>