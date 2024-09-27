<!DOCTYPE html>
<html lang="en">

<head>

    <title>Tea Menu</title>
    <link rel="stylesheet" href="css/styles.css">

</head>

<body class="gradient-background">
    <div class="container">

        <!--Navigation bar-->
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


        <?php
        include "../config.php";
        $category_id = isset($_GET['category_id']);
        echo "<script>console.log('PHP Value: " . addslashes($category_id) . "');</script>";
        if ($category_id > 0) {
            $stmt = $link->prepare("SELECT * FROM products WHERE category_id = ?");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();


        ?>
            <div class="main">
                <div class="menu-box">
                    <div class="title-bar">
                        <h1>Products</h1>
                    </div>
                    <div class="menu-box-grid">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="product-item">';
                            echo '<button class="menu-box-item" onclick="sendImage(' . $row["product_id"] . ')">';
                            echo '<img src="products_crud/display_product_image.php?product_id=' . $row["product_id"] . '" title="' . htmlspecialchars($row["product_name"]) . '" id="img' . $row["product_id"] . '">';
                            echo '<h3 class="item-cost">' . htmlspecialchars($row["product_name"]) . '<br>$' . number_format($row["Price"], 2) . '</h3>';
                            echo '</button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No products found for this category.</p>';
                    }
                    $stmt->close();
                } else {
                    echo '<p>Invalid category.</p>';
                }
                $link->close();
                    ?>
                    </div>
                </div>
            </div>
    </div>

    <!--Order summary-->
    <div class="order-box">
        <div class="order-box-inner">
            <div class="order-number"></div>
            <hr>
            <div class="order-info"></div>
            <hr>
            <!--Payment method section with buttons-->
            <div class="payment-method">
                <h2>Payment method</h2>
                <div class="payment-options">
                    <button class="cash-option"><img src="logos/cash_icon.png"></button>
                    <button class="card-option"><img src="logos/card_icon.png"></button>
                </div>
            </div>
        </div>
        <div class="order-button-box">
            <button class="order-button" id="order-button">Place order <img src="logos/arrow.png" id="order-arrow"></button>
        </div>
    </div>
    </div>
    </div>
</body>

<script src="js/cartUpdater.js"></script>

<!-- Script path -->
<script src="js/paymentMethod.js"></script>
<!-- Script file -->
<script>
    updateCart();
    enablePaymentUpdate();
    updateColor();

    function sendImage(imageNumber) {
        const img = document.getElementById('img' + imageNumber);
        const id = imageNumber;
        const imgSrc = encodeURIComponent(img.src);
        const h3 = img.nextElementSibling.innerHTML.split('<br>');

        const title = h3[0].trim().replace('<br>', '');
        const price = parseFloat(h3[1].replace('$', '').trim());

        window.location.href = `customization_menu.html?imgSrc=${imgSrc}&id=${id}&title=${title}&price=${price}`;
    }
</script>

</html>