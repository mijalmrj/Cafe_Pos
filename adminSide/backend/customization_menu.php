<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customization Menu</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="gradient-background">
    <div class="container">
        <!--Navigation bar-->
        <header class="navbar">
            <div class="navhome">
                <a href="index.php"><img src="logos/home.png"></a>
                <a href="index.php"><h2>Northside Caf&eacute;</h2></a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="settings.html"><img src="logos/settings.png"></a>
                    <a href="sales/all_sales.php"><img src="logos/analytics.png"></a>
                </div>
                <div class="generallinks">
                    <a href="transactions.html"><img src="logos/print.png"></a>
                    <a href="user.php"><img src="logos/user.png"></a>
                </div>
            </div>
        </header>

        <div class="main">
            <!--Menu box-->
            <form action="process_order.php" method="POST">
                <div class="custom-menu">
                    <div class="customization-title">
                        <h1>SELECT/CUSTOMIZE</h1>
                    </div>
                    <div class="menu-container">
                        <div class="size-menu">
                            <div class="selection-item-box">
                                <img id="selection-img" alt="selection-img">
                                <h3 id="item-name"></h3>
                                <input type="hidden" name="item_name" id="hidden-item-name">
                                <input type="hidden" name="cost" id="hidden-cost">
                            </div>
                            
                            <button class="size-option" type="button" id="small" onclick="setSize('small', 0)">Small</button>
                            <button class="size-option" type="button" id="large" onclick="setSize('large', 1)">Large +$1</button>
                            <input type="hidden" name="item_size" id="selected-size" value="small">
                            
                            <div class="add-on-div">
                                <label class="add-on-option">
                                    <input type="checkbox" name="takeaway" id="takeaway"> Takeaway
                                </label>
                                <label class="add-on-option">
                                    <input type="checkbox" name="sugar" id="sugar"> Sugar
                                </label>
                            </div>
                        </div>

                        <div class="milk-menu">
                            <button class="milk-option" type="button" id="regular" onclick="setMilk('regular')">Regular milk</button>
                            <button class="milk-option" type="button" id="almond" onclick="setMilk('almond')">Almond milk</button>
                            <button class="milk-option" type="button" id="soy" onclick="setMilk('soy')">Soy milk</button>
                            <button class="milk-option" type="button" id="lactose" onclick="setMilk('lactose')">Lactose free</button>
                            <input type="hidden" name="milk_type" id="selected-milk" value="regular">
                        </div>
                    </div>
                </div>

                <!--Order summary-->
                <div class="order-box">
                    <div class="order-button-box">
                        <button class="order-button" type="submit" id="order-button">Place order <img src="logos/arrow.png" id="order-arrow"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JS script file -->
    <script>
        let selectionPrice = 0;
        const sizeElement = document.getElementById('selected-size');
        const milkElement = document.getElementById('selected-milk');
        const hiddenCostElement = document.getElementById('hidden-cost');

        function setSize(size, extraCost) {
            selectionPrice = extraCost;
            sizeElement.value = size;
            hiddenCostElement.value = selectionPrice;
        }

        function setMilk(milk) {
            milkElement.value = milk;
        }

        window.onload = function() {
            const imgSrc = getParameterByName('imgSrc');
            if (imgSrc) {
                const img = document.getElementById('selection-img');
                img.src = imgSrc;

                const itemName = getParameterByName('title');
                document.getElementById('item-name').innerText = itemName;
                document.getElementById('hidden-item-name').value = itemName;

                const price = parseFloat(itemName.split('$')[1]);
                selectionPrice = price;
                hiddenCostElement.value = selectionPrice;
            }
        };
    </script>
</body>
</html>
