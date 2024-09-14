<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Menu</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="gradient-background">
    <div class="container">
        <!-- Navigation bar -->
        <header class="navbar">
            <div class="navhome">
                <a href="../frontend/index.php"><img src="logos/home.png" alt="Home"></a>
                <a href="../frontend/index.php"><h2>Northside Café</h2></a>
            </div>
            <div class="navlinks">
                <div class="adminlinks">
                    <a href="../frontend/settings.html"><img src="logos/settings.png" alt="Settings"></a>
                    <a href="../frontend/report.html"><img src="logos/analytics.png" alt="Report"></a>
                </div>
                <div class="generallinks">
                    <a href="../frontend/transactions.html"><img src="logos/print.png" alt="Transactions"></a>
                    <a href="../frontend/user.html"><img src="logos/user.png" alt="User"></a>
                </div>
            </div>
        </header>

        <div class="main">
            <!-- Menu box -->
            <div class="menu-box">
                <div class="title-bar"><h1>COFFEES</h1></div>
                <div class="menu-box-grid" id="menu-box-grid">
                    <!-- Menu items will be loaded here by JavaScript -->
                </div>
            </div>

            <!-- Order summary -->
            <div class="order-box">
                <div class="order-box-inner">
                    <div class="order-number"></div>
                    <hr>
                    <div class="order-info"></div>
                    <hr>
                    <!-- Payment method section with buttons -->
                    <div class="payment-method">
                        <h2>Payment method</h2>
                        <div class="payment-options">
                            <button class="cash-option"><img src="logos/cash_icon.png" alt="Cash"></button>
                            <button class="card-option"><img src="logos/card_icon.png" alt="Card"></button>
                        </div>
                    </div>
                </div>
                <div class="order-button-box">
                    <button class="order-button" id="order-button">Place order <img src="logos/arrow.png" id="order-arrow" alt="Arrow"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script file -->
    <script>
        function loadMenu() {
            fetch('fetch_menu.php')
                .then(response => response.json())
                .then(data => {
                    const menuBoxGrid = document.getElementById('menu-box-grid');
                    menuBoxGrid.innerHTML = ''; // Clear existing items

                    data.forEach(item => {
                        const button = document.createElement('button');
                        button.className = 'menu-box-item';
                        button.onclick = () => sendImage(item.item_id);

                        const img = document.createElement('img');
                        img.src = 'images/' + item.item_image;
                        img.title = item.item_name;
                        img.id = 'img' + item.item_id;
                        img.alt = item.item_name;

                        const h3 = document.createElement('h3');
                        h3.className = 'item-cost';
                        h3.innerHTML = `${item.item_name}<br>$${item.item_price}`;

                        button.appendChild(img);
                        button.appendChild(h3);
                        menuBoxGrid.appendChild(button);
                    });
                })
                .catch(error => console.error('Error loading menu:', error));
        }

        function sendImage(imageNumber) {
            const img = document.getElementById('img' + imageNumber);
            const imgSrc = encodeURIComponent(img.src);
            const h3 = img.nextElementSibling;
            window.location.href = `customization_menu.html?imgSrc=${imgSrc}&title=${h3.innerHTML}`;
        }

        // Load the menu on page load
        window.onload = loadMenu;
    </script>
</body>

</html>
