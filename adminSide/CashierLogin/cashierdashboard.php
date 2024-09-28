<?php
require_once "../config.php";
session_start();

//// Check if user is logged in
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    //header("location: login.php");
    //exit;
//}
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
        <div class="container">
            <h1>Cashier Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="../frontend/index.php">Home</a></li>
                    <li><a href="sale_management.php">Sale Management</a></li>
                    <li><a href="report.php">Report</a></li>
                    <li><a href="update_account.php">Update Account</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Welcome Cashier</h2>
            <div class="summary">
                <h3>Sales Summary</h3>
                <!-- Display summary and images -->
                <p>Total Sales Today: $<span id="total-sales">0.00</span></p>
                <img src="../images/sales_summary.png" alt="Sales Summary" class="summary-image">
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Northside Café</p>
        </div>
    </footer>
    <style>
        /* Reset some default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f2f2;
    color: #333;
    line-height: 1.6;
}

/* Container */
.container {
    width: 90%;
    max-width: 1200px;
    margin: auto;
    overflow: hidden;
    padding: 20px 0;
}

/* Header Styling */
header {
    background-color: #b5651d;
    color: #fff;
    padding: 20px 0;
    margin-bottom: 20px;
}

header .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

header h1 {
    font-size: 2em;
    letter-spacing: 2px;
}

nav ul {
    list-style: none;
    display: flex;
}

nav ul li {
    margin-left: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 1em;
    padding: 5px 10px;
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 4px;
}

nav ul li a:hover {
    background-color: #fff;
    color: #b5651d;
}

/* Main Content Styling */
main .container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

main h2 {
    margin-bottom: 20px;
    font-size: 1.8em;
    color: #b5651d;
}

.summary {
    text-align: center;
}

.summary h3 {
    margin-bottom: 15px;
    font-size: 1.5em;
    color: #333;
}

.summary p {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.summary-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Footer Styling */
footer {
    background-color: #b5651d;
    color: #fff;
    text-align: center;
    padding: 15px 0;
    margin-top: 40px;
}

footer p {
    font-size: 1em;
}

/* Responsive Design */
@media (max-width: 768px) {
    header .container {
        flex-direction: column;
        align-items: flex-start;
    }

    nav ul {
        flex-direction: column;
        width: 100%;
    }

    nav ul li {
        margin: 10px 0;
    }

    main .container {
        padding: 20px;
    }

    .summary h3 {
        font-size: 1.3em;
    }

    .summary p {
        font-size: 1em;
    }
}

/* Additional Styling for Buttons and Links (if needed) */
button, .btn {
    display: inline-block;
    background-color: #b5651d;
    color: #fff;
    border: none;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 1em;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover, .btn:hover {
    background-color: #a04d0b;
}

    </style>
</body>
</html>

<?php
mysqli_close($link);
?>
