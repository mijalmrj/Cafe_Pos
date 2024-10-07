<?php
session_start();
require_once "../config.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="../frontend/index.php">Home</a></li>
                    <li><a href="../frontend/categories_crud/manage_category.php">Category Management</a></li>
                    <li><a href="../frontend/products_crud/manage_items.php">Product Management</a></li>
                    <li><a href="sale_management.php">Sale Management</a></li>
                    <li><a href="report.php">Report</a></li>
                    <li><a href="user_management.php">User Management</a></li>
                    <li><a href="../CashierLogin/update_account.php">Update Account</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Welcome Admin</h2>
            <div class="summary">
                <h3>Sales Summary</h3>
                <p>Total Sales Today: $<span id="total-sales">0.00</span></p>
                <img src="../images/sales_summary.png" alt="Sales Summary" class="summary-image">
            </div>
            <div class="links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="category_management.php">Manage Categories</a></li>
                    <li><a href="product_management.php">Manage Products</a></li>
                    <li><a href="sale_management.php">Manage Sales</a></li>
                    <li><a href="report.php">Generate Reports</a></li>
                    <li><a href="user_management.php">Manage Users</a></li>
                </ul>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Northside Caf&eacute;</p>
        </div>
    </footer>

    <style>
        /* Styles similar to the cashier dashboard, can be customized further */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            line-height: 1.6;
        }
        header {
            background-color: #b5651d;
            color: #fff;
            padding: 20px 0;
            margin-bottom: 20px;
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
        }
        main .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #b5651d;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</body>
</html>

<?php
mysqli_close($link);
?>
