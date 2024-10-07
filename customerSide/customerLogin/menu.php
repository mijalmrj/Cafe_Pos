<?php
session_start();
require_once "../config.php";

// Initialize cart in session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Fetch items from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($link, $sql);

// Create an array to hold menu items by category
$menu_items = array();

// Process the results
while ($row = mysqli_fetch_assoc($result)) {
    $category_id = $row['category_id'];
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $price = $row['Price'];
    
    // Initialize category if not already done
    if (!isset($menu_items[$category_id])) {
        $menu_items[$category_id] = array();
    }
    
    // Add the product to the appropriate category
    $menu_items[$category_id][] = array('id' => $product_id, 'name' => $product_name, 'price' => $price);
}

// Define categories (this could be dynamically fetched too)
$categories = array(
    1 => 'Coffee',
    2 => 'Iced Drinks',
    3 => 'Teas',
    4 => 'Soft Drinks'
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Northside Caf&eacute;</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/paymentMethod.js" defer></script>
    <script>
        function addToCart(productId, productName, price) {
    if (productId === 4) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert(productName + " has been added to your cart!");
            } else {
                alert("Error adding item to cart.");
            }
        };
        xhr.send("product_id=" + productId + "&product_name=" + encodeURIComponent(productName) + "&product_price=" + price);
    } else {
        // Show customization box for other products
        document.getElementById('customization-box').style.display = 'block';
        document.getElementById('customization-form').setAttribute('data-product-id', productId);
        document.getElementById('customization-form').setAttribute('data-product-name', productName);
        document.getElementById('customization-form').setAttribute('data-product-price', price);
    }
}

        

function submitCustomization() {
    var form = document.getElementById('customization-form');
    var productId = form.getAttribute('data-product-id');
    var productName = form.getAttribute('data-product-name');
    var price = form.getAttribute('data-product-price');

    var formData = new FormData(form);
    formData.append('product_id', productId); // Add product ID to the form data
    formData.append('product_name', productName); // Add product name to the form data
    formData.append('product_price', price); // Add product price to the form data

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert(productName + " has been added to your cart with customization!");
                removeCustomization();
            } else {
                alert("Error adding item to cart.");
            }
        }
    };
    xhr.send(formData); // Send the form data with customization
}


        function removeCustomization() {
            document.getElementById('customization-form').reset(); // Clear the form fields
            document.getElementById('customization-box').style.display = 'none'; // Hide the customization box
        }

        function showCustomizationBox() {
            document.getElementById('customization-box').style.display = 'block'; // Show customization box
        }
    </script>
    <style>
        /* General styling for the Menu Section */
        #menu {
            background-color: #f8f8f8; /* Light background */
            padding: 60px 20px; /* Add padding for spacing */
            text-align: center;
        }

        #menu h1 {
            font-size: 36px; /* Increase the font size for the main title */
            color: #333; /* Darker color for the title */
            font-family: 'Copperplate', sans-serif; /* Apply the same font family */
            margin-bottom: 40px; /* Add some space below the title */
        }

        /* View Cart Button Styles */
        .view-cart-btn {
            background-color: #b5651d; /* Button background color */
            color: #fff; /* Button text color */
            padding: 10px 20px; /* Button padding */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners for the button */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size for the button */
            margin-bottom: 20px; /* Space below the button */
            text-decoration: none; /* Remove underline */
            display: inline-block; /* Align correctly */
        }

        .view-cart-btn:hover {
            background-color: #a04d0b; /* Darker color on hover */
        }

        /* Additional existing styles */
        .menu-category {
            margin-bottom: 50px; /* Add space between the categories */
        }
        
        .menu-category h2 {
            font-size: 28px; /* Larger font size for the category name */
            color: #b5651d; /* A soft brown color that fits the cafe theme */
            margin-bottom: 20px; /* Space below the category heading */
            font-family: 'Montserrat', sans-serif; /* Stylish font */
        }

        .menu-category ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0;
            margin: 0;
        }

        .menu-category li {
            font-size: 20px; /* Font size for menu items */
            color: #555; /* Darker text color for visibility */
            padding: 10px 0; /* Space between items */
            border-bottom: 1px solid #ddd; /* Line between items */
        }

        .menu-category li:last-child {
            border-bottom: none; /* Remove the bottom border for the last item */
        }

        .menu-category li:hover {
            color: #b5651d; /* Change the color on hover to the brown theme */
            cursor: pointer; /* Add pointer cursor on hover */
            background-color: #f0e4d7; /* Soft background color on hover */
            transition: background-color 0.3s ease, color 0.3s ease; /* Smooth hover effect */
        }

        /* Grid layout for Menu Categories */
        .menu-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid */
            gap: 20px; /* Space between grid items */
            max-width: 1200px; /* Maximum width for the section */
            margin: 0 auto; /* Center the section */
        }

        .menu-category {
            padding: 20px;
            background-color: #fff; /* White background for each category */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
            border-radius: 8px; /* Slightly rounded corners */
        }

        .menu-category h2 {
            border-bottom: 2px solid #b5651d; /* Brown line under the category name */
            padding-bottom: 10px; /* Space below the category name */
        }

        .menu-category ul {
            padding-left: 0; /* Remove default padding */
        }

        /* Styling for price display */
        .price {
            font-size: 1.5em; /* Increase font size */
            font-weight: bold; /* Make font bold */
            color: #b5651d; /* Use a contrasting color, like a brown shade */
            padding: 10px; /* Add some padding for spacing */
            background-color: #f0e4d7; /* Light background color for better contrast */
            border-radius: 5px; /* Rounded corners for the background */
            margin-top: 10px; /* Space above the price */
            display: inline-block; /* Ensure it fits its content */
        }

        .add-to-cart, .customize-btn {
            display: inline-block;
            background-color: #b5651d; /* Button background color */
            color: #fff; /* Button text color */
            padding: 10px 20px; /* Button padding */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners for the button */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size for the button */
            margin-top: 10px; /* Space above buttons */
        }

        .add-to-cart:hover, .customize-btn:hover {
            background-color: #a04d0b; /* Darker color on hover */
        }

        /* Customization Box Styles */
        .customize {
            display: none; /* Initially hidden */
            background-color: #fff; /* White background */
            padding: 20px; /* Padding around the box */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            position: fixed; /* Fixed positioning */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Offset to truly center */
            z-index: 1000; /* Ensure it appears on top */
        }

        .customize h1 {
            font-size: 24px; /* Title size */
            margin-bottom: 20px; /* Space below title */
        }

        .form-group {
            margin-bottom: 20px; /* Space below form fields */
        }

        .form-group label {
            display: block; /* Block display for labels */
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold text for labels */
        }

        .form-group select {
            padding: 10px; /* Padding for select inputs */
            width: 100%; /* Full width */
            max-width: 300px; /* Max width for select inputs */
            border: 1px solid #ccc; /* Border for select inputs */
            border-radius: 5px; /* Rounded corners */
        }

        .customization-btns {
            margin-top: 20px; /* Space above buttons */
        }

        .customization-btns button {
            margin-right: 10px; /* Space between buttons */
            padding: 10px 20px; /* Padding for buttons */
            background-color: #b5651d; /* Button background color */
            color: #fff; /* Button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
        }

        .customization-btns button:hover {
            background-color: #a04d0b; /* Darker color on hover */
        }
    </style>
</head>
<body>

<div id="menu">
    <h1>Menu</h1>
    <a href="view_cart.php" class="view-cart-btn">View Cart</a>
    <div class="menu-section">
        <?php foreach ($categories as $category_id => $category_name): ?>
            <div class="menu-category">
                <h2><?php echo $category_name; ?></h2>
                <ul>
                    <?php if (isset($menu_items[$category_id])): ?>
                        <?php foreach ($menu_items[$category_id] as $item): ?>
                            <li>
                                <?php echo $item['name']; ?> - <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                                <button class="add-to-cart" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo $item['name']; ?>', <?php echo $item['price']; ?>);">Add to Cart</button>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No items available in this category.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Customization Form -->
<div class="customize" id="customization-box">
    <h1>Customize Your Drink</h1>
    <form id="customization-form" onsubmit="event.preventDefault(); submitCustomization();">
        <div class="form-group">
            <label for="size">Size:</label>
            <select id="size" name="size">
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sugar">Sugar Level:</label>
            <select id="sugar" name="sugar">
                <option value="No Sugar">No Sugar</option>
                <option value="1 Sugar">1 Sugar</option>
                <option value="2 Sugars">2 Sugars</option>
                <option value="3 Sugars">3 Sugars</option>
            </select>
        </div>
        <div class="form-group">
            <label for="milk">Milk Type:</label>
            <select id="milk" name="milk">
                <option value="None">None</option>
                <option value="Whole Milk">Whole Milk</option>
                <option value="Skim Milk">Skim Milk</option>
                <option value="Almond Milk">Almond Milk</option>
                <option value="Soy Milk">Soy Milk</option>
            </select>
        </div>
        <div class="customization-btns">
            <button type="button" onclick="removeCustomization()">Cancel</button>
            <button type="submit">Add to Cart</button>
        </div>
    </form>
</div>

</body>
</html>
