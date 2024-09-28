<?php
require_once "../../config.php";
session_start();

$userId = $_SESSION['logged_user_id'];

// Prepare SQL query
$query = "
    SELECT o.order_id, o.user_id, o.shipping_time, o.total_amount, od.product_id, od.product_name, od.size
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
";
$stmt = $link->prepare($query);
if (!$stmt) {
    die("Database query preparation failed: " . $link->error);
}

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

$stmt->close();

// Function to generate the receipt for all orders
function generateReceipt($orders)
{
    $receiptContent = "<h1>All Orders Receipt</h1>";
    foreach ($orders as $orderId => $order) {
        $receiptContent .= "<h2>Order ID: $orderId</h2>";
        $receiptContent .= "<p>Shipping Time: " . htmlspecialchars($order['shipping_time']) . "</p>";
        $receiptContent .= "<p>Total Amount: $" . htmlspecialchars($order['total_amount']) . "</p>";
        $receiptContent .= "<h3>Items:</h3><ul>";
        foreach ($order['items'] as $item) {
            $receiptContent .= "<li>" . htmlspecialchars($item['product_name']) . " (Size: " . htmlspecialchars($item['size']) . ")</li>";
        }
        $receiptContent .= "</ul><hr>";
    }
    return $receiptContent;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_all_receipts'])) {
    $receiptHTML = generateReceipt($orders);

    // Set headers for downloading the HTML file
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="all_orders_receipt.html"');

    // Output the HTML content
    echo $receiptHTML;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Generate Reports</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="grey-background">
    <!-- Navigation bar -->
    <header class="navbar">
        <div class="navhome">
            <a href="../index.html"><img src="../logos/home.png" id="coffee-logo"></a>
            <a href="../index.html">
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

    <div class="container">
        <div class="report-header">
            <h1>GENERATE REPORTS</h1>
        </div>

        <div class="report-summary">
            <div class="date">
                <p id="todaydate"></p>
            </div>
            <p>TOTAL SALES<br><span class="needsbackend">$0</span></p>
            <p>TOTAL TRANSACTIONS<br><span class="needsbackend">0</span></p>
        </div>

        <div class="report-filter">
            <div class="form-group">
                <label for="staffselection">STAFF MEMBER</label>
                <select id="staffselection" name="staffselection">
                    <option value="all">ALL</option>
                </select>
            </div>
            <div class="form-group">
                <label for="startdate">START DATE:</label>
                <input type="date" id="startdate" name="startdate">
            </div>
            <div class="form-group">
                <label for="enddate">END DATE:</label>
                <input type="date" id="enddate" name="enddate">
            </div>
            <div class="form-group">
                <form method="post">
                    <button type="submit" name="download_all_receipts" style="background-color:black"><img src="../logos/printer_beige.png" id="printer-logo" alt="Download All Receipts"></button>
                </form>
            </div>
        </div>

        <div class="report-container">
            <div class="report-graph"></div>

            <div class="report-content">
                <h1>SALES</h1>
                <div class="filter-info">
                    <h2>STAFF: <span id="staff-output">ALL STAFF</span></h2>
                    <hr>
                    <h2>PERIOD: <span id="startdate-output">STARTDATE</span> TO <span id="enddate-output">ENDDATE</span></h2>
                </div>

                <div class="report-transactions">
                    <table>
                        <tr>
                            <th>DATE</th>
                            <th>ORDER</th>
                            <th>PRICE</th>
                            <th>STAFF</th>
                        </tr>
                        <?php
                        if (count($orders) > 0) {
                            foreach ($orders as $orderId => $order) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($order['shipping_time']) . "</td>";
                                echo "<td>";
                                foreach ($order['items'] as $item) {
                                    echo htmlspecialchars($item['product_name']) . " (Size: " . htmlspecialchars($item['size']) . ")<br>";
                                }
                                echo "</td>";
                                echo "<td>$" . htmlspecialchars($order['total_amount']) . "</td>";
                                echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No transactions found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    // Script for displaying today's date:
    document.addEventListener("DOMContentLoaded", function() {
        // Get the current date
        const today = new Date();
        // Format the date
        const formattedDate = today.toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        // Set the date in the element with id 'todaydate'
        document.getElementById('todaydate').textContent = `${formattedDate}`;
    });

    // Script for displaying user input:
    document.addEventListener("DOMContentLoaded", function() {
        // Obtaining elements:
        const staffSelection = document.getElementById("staffselection");
        const startDateInput = document.getElementById("startdate");
        const endDateInput = document.getElementById("enddate");
        const staffOutput = document.getElementById("staff-output");
        const startDateOutput = document.getElementById("startdate-output");
        const endDateOutput = document.getElementById("enddate-output");

        // Updating display:
        function updateReportInfo() {
            const staffValue = staffSelection.value;
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;

            // Updating text:
            staffOutput.textContent = staffValue !== "all" ? staffValue : "ALL STAFF";
            startDateOutput.textContent = startDateValue ? new Date(startDateValue).toLocaleDateString() : "START DATE";
            endDateOutput.textContent = endDateValue ? new Date(endDateValue).toLocaleDateString() : "END DATE";
        }

        // Attach event listeners
        staffSelection.addEventListener("change", updateReportInfo);
        startDateInput.addEventListener("change", updateReportInfo);
        endDateInput.addEventListener("change", updateReportInfo);
    });
</script>

</html>