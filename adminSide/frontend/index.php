<?php
session_start(); // Start the session to access user role

// Include database configuration
include "../config.php";

// Assuming the user role is stored in the session
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Index</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="gradient-background">
    <div class="container">

        <!-- Navigation bar -->
        <header class="navbar">
            <div class="navhome">
                <a href="index.html"><img src="logos/coffee_logo.png" id="coffee-logo"></a>
                <a href="index.php">
                    <h2>Northside Café</h2>
                </a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">

                        <a href="settings.html"><img src="logos/settings.png" alt="Settings"></a>
                        <a href="report.html"><img src="logos/analytics.png" alt="Report"></a>

                    </div>
                <div class="generallinks">

                        <a href="transactions.html"><img src="logos/print.png" alt="Transactions"></a>
                        <a href="user.html"><img src="logos/user.png" alt="User"></a>

                    </div>
            </div>
        </header>

        <?php
        $sql = "SELECT * FROM categories";
        $result = $link->query($sql);
        ?>

        <div class="main">
            <div class="menu-box">
                <div class="title-bar">
                    <h1>CATEGORIES</h1>
                </div>
                
                <div class="menu-box-grid">
                    <a href="coffee_menu.html">
                        <div class="menu-box-item">
                            <img src="images/coffees_image.png" title="coffees">
                            <h3>Coffees</h3>
                        </div>
                    </a>

                    <a href="iced_drinks_menu.html">
                        <div class="menu-box-item">
                            <img src="images/iced_drinks_image.png" title="iced drinks">
                            <h3>Iced Drinks</h3>
                        </div>
                    </a>

                    <a href="tea_menu.html">
                        <div class="menu-box-item">
                            <img src="images/teas_image.png" title="teas">
                            <h3>Teas</h3>
                        </div>
                    </a>

                    <a href="soft_drinks_menu.html">
                        <div class="menu-box-item">
                            <img src="images/soft_drinks.png" title="soft drinks">
                            <h3>Soft Drinks</h3>
                        </div>
                    </a>
                </div>
            </div>
                    
            </div>
            </div>

            <!-- Order summary -->
            <div class="order-box">
                <div class="order-box-inner">
                    <div class="order-number"></div>
                    <hr>
                    <div class="order-info"></div>
                    <hr>
                    <div class="payment-method">
                        <h2>Payment method</h2>
                        <img src="logos/cash_icon.png" alt="Cash">
                        <img src="logos/card_icon.png" alt="Card">
                    </div>
                </div>
                <div class="order-button">
                    <div class="order-button-logo">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="js/cartUpdater.js"></script>
<script src="js/paymentMethod.js"></script>
<script>
    updateCart();
    enablePaymentUpdate();
    updateColor();
</script>
