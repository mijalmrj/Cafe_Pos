<?php
require_once "../config.php"; // Include database configuration
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if cart is not empty and is an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("location: view_cart.php"); // Redirect to cart if empty
    exit;
}

// Initialize variables for order details
$total_amount = 0.00;
$shipping_method = $shipping_time = $shipping_location = "";
$shipping_method_err = $shipping_time_err = $shipping_location_err = "";
$order_confirmed = false; // Variable to track if the order is confirmed

// Process the order form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate shipping method
    if (empty(trim($_POST["shipping_method"]))) {
        $shipping_method_err = "Please select a shipping method.";
    } else {
        $shipping_method = trim($_POST["shipping_method"]);
    }

    // Validate shipping time
    if (empty(trim($_POST["shipping_time"]))) {
        $shipping_time_err = "Please select a shipping time.";
    } else {
        $shipping_time = trim($_POST["shipping_time"]);
    }

    // Validate shipping location
    if (empty(trim($_POST["shipping_location"]))) {
        $shipping_location_err = "Please enter a shipping location.";
    } else {
        $shipping_location = trim($_POST["shipping_location"]);
    }

    // Calculate total amount based on cart items
    if (empty($shipping_method_err) && empty($shipping_time_err) && empty($shipping_location_err)) {
        foreach ($_SESSION['cart'] as $item) {
            // Ensure each item is an array with product_id and quantity
            if (is_array($item) && isset($item['product_id']) && isset($item['quantity'])) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];

                // Query to get the price of the product
                $sql = "SELECT Price FROM products WHERE product_id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $product_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $price);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    // Add the price * quantity to the total amount
                    $total_amount += $price * $quantity;
                }
            } else {
                echo "Invalid cart item format.";
            }
        }

        // Insert the order into the database
        $sql = "INSERT INTO `order` (total_amount, shipping_method, shipping_time, shipping_location, order_status) VALUES (?, ?, ?, ?, 'Pending')";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "dsss", $total_amount, $shipping_method, $shipping_time, $shipping_location);

            if (mysqli_stmt_execute($stmt)) {
                $order_id = mysqli_insert_id($link); // Get the last inserted order ID
                $order_confirmed = true; // Mark the order as confirmed
                // Store order ID in session to use for receipt download
                $_SESSION['order_id'] = $order_id;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif; /* General font for the page */
            background-color: #f9f9f9; /* Light background color */
            margin: 0; /* Remove default margin */
            padding: 20px; /* Add padding */
        }

        .dashboard_wrapper {
            max-width: 600px; /* Limit the width of the order form */
            margin: 0 auto; /* Center the form on the page */
            padding: 20px; /* Add padding */
            background-color: #ffffff; /* White background for the form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h2 {
            text-align: center; /* Center the heading */
            color: #333; /* Dark text color */
            margin-bottom: 20px; /* Space below the heading */
        }

        .form-group {
            margin-bottom: 15px; /* Space between form groups */
        }

        label {
            display: block; /* Display labels as block for spacing */
            font-weight: bold; /* Bold text for labels */
            margin-bottom: 5px; /* Space below labels */
        }

        .form-control {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Padding for inputs */
            border: 1px solid #ccc; /* Light border */
            border-radius: 4px; /* Rounded corners */
            box-sizing: border-box; /* Include padding in width */
        }

        .text-danger {
            color: red; /* Red color for error messages */
            font-size: 0.9em; /* Slightly smaller font for error messages */
        }

        .btn {
            background-color: #b5651d; /* Button background color */
            color: #fff; /* Button text color */
            padding: 10px 20px; /* Button padding */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size for the button */
            display: block; /* Full width */
            margin: 20px auto; /* Center the button */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        .btn:hover {
            background-color: #a04d0b; /* Darker color on hover */
        }

        .confirmation {
            text-align: center; /* Center confirmation message */
            margin-top: 20px; /* Space above confirmation message */
        }
    </style>
</head>
<body>
    <div class="dashboard_wrapper">
        <h2>Place an Order</h2>
        <?php if ($order_confirmed): ?>
            <div class="confirmation">
                <h3>Order Confirmed! Your Order ID is: <?php echo $order_id; ?></h3>
                <h4>You can download your receipt below:</h4>
                <a href="download_receipt.php?order_id=<?php echo $order_id; ?>" class="btn">Download Receipt</a>
            </div>
        <?php else: ?>
            <form action="place_order.php" method="post">
                <div class="form-group">
                    <label>Shipping Method</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="">Select</option>
                        <option value="pick up">Pick Up</option>
                        <option value="deliver">Deliver</option>
                    </select>
                    <span class="text-danger"><?php echo $shipping_method_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Shipping Time</label>
                    <input type="datetime-local" name="shipping_time" class="form-control" required>
                    <span class="text-danger"><?php echo $shipping_time_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Shipping Location</label>
                    <input type="text" name="shipping_location" class="form-control" placeholder="Enter shipping location" required>
                    <span class="text-danger"><?php echo $shipping_location_err; ?></span>
                </div>
                <button class="btn" type="submit">Place Order</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
mysqli_close($link); // Close the database connection
?>
