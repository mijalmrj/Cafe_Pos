<?php
require_once "../config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['logged_user_id'];

// Prepare SQL query
$query = "
    SELECT o.order_id, o.shipping_time, o.total_amount, od.order_type, od.size, od.quantity
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    WHERE o.user_id = ?
";

// Prepare the statement
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

// Check if any orders were found
if ($result->num_rows === 0) {
    echo "No orders found for this user.";
} else {
    // Fetch and process the results as needed
    while ($row = $result->fetch_assoc()) {
        // Process each row (e.g., display order details)
        echo "Order ID: " . htmlspecialchars($row['order_id']) . "<br>";
        echo "Shipping Time: " . htmlspecialchars($row['shipping_time']) . "<br>";
        echo "Total Amount: " . htmlspecialchars($row['total_amount']) . "<br>";
        echo "Order Type: " . htmlspecialchars($row['order_type']) . "<br>";
        echo "Size: " . htmlspecialchars($row['size']) . "<br>";
        echo "Quantity: " . htmlspecialchars($row['quantity']) . "<br>";
        echo "<hr>";
    }
}

// Close the statement
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Transactions (Admin)</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="manage-body">
    <div class="container">
        <header class="navbar">
            <div class="navhome">
                <a href="index.html"><img src="logos/home.png" id="coffee-logo"></a>
                <a href="index.html">
                    <h2>Northside Café</h2>
                </a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="settings.html"><img src="logos/settings.png"></a>
                    <a href="report.html"><img src="logos/analytics.png"></a>
                </div>
                <div class="generallinks">
                    <a href="transactions_admin.html"><img src="logos/print.png"></a>
                    <a href="user.html"><img src="logos/user.png"></a>
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['shipping_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['order_type']) . " (Size: " . htmlspecialchars($row['size']) . ", Qty: " . htmlspecialchars($row['quantity']) . ")</td>";
                            echo "<td>$" . htmlspecialchars($row['total_amount']) . "</td>";
                            echo "<td><!-- Staff Name --></td>";
                            echo "<td><button onclick='printReceipt(" . htmlspecialchars($row['order_id']) . ")'>Print</button></td>";
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

    <script src="js/transactions.js"></script>
</body>

</html>



<script src="js/transactions.js">
    generateTransReports();
</script>