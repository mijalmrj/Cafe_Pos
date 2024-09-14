<?php
// fetch_menu.php
include '../config.php';
// Fetch order details from the database using order ID (passed via GET)
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $order_no = $row['order_no'];
    $order_details = $row['order_details'];
    $order_amount = $row['order_amount'];
} else {
    echo "Order not found.";
    exit;
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirmed Order</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="receipt-body">
    <div class="container">
        <!-- Navigation bar -->
        <header class="navbar">
            <div class="navhome">
                <a href="../frontend/index.php"><img src="logos/home.png"></a>
                <a href="../frontend/index.php"><h2>Northside Café</h2></a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="../frontend/settings.html"><img src="logos/settings.png"></a>
                    <a href="../frontend/report.html"><img src="logos/analytics.png"></a>
                </div>
                <div class="generallinks">
                    <a href="../frontend/transactions.html"><img src="logos/print.png"></a>
                    <a href="../frontend/user.html"><img src="logos/user.png"></a>
                </div>
            </div>
        </header>

        <div class="main-receipts">
            <div class="receipt-box">
                <h1>Order Confirmed</h1>
                <div class="receipt-details">
                    <h2 id="receipt-orderno">Order No: <?php echo htmlspecialchars($order_no); ?></h2>
                    <div id="receipt-orderdetails"><?php echo nl2br(htmlspecialchars($order_details)); ?></div>
                    <h2 id="receipt-orderamount">Amount: $<?php echo number_format($order_amount, 2); ?></h2>
                </div>
                <div class="receipt-print-option">
                    <h>PRINT RECEIPT?</h>
                    <hr>
                    <div>
                        <button class="receipt-button" onclick="window.print();">YES</button>
                        <button class="receipt-button" onclick="window.location.href='index.php';">NO</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
