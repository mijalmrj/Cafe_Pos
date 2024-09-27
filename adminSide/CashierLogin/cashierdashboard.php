<?php
require_once "../config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <header>
        <h1>Cashier Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="sale_management.php">Sale Management</a></li>
                <li><a href="report.php">Report</a></li>
                <li><a href="update_account.php">Update Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
        <div class="summary">
            <h3>Sales Summary</h3>
            <!-- Display summary and images -->
            <p>Total Sales Today: $<span id="total-sales">0.00</span></p>
            <img src="../images/sales_summary.png" alt="Sales Summary">
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Northside Café</p>
    </footer>
</body>
</html>

<?php
mysqli_close($link);
?>
