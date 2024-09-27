<?php
session_start();

// Clear the cart session variable
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']); // Remove the cart session variable
}

// Optionally, you can set a message to inform the user that the cart has been cleared
$_SESSION['message'] = "Your cart has been cleared.";

// Redirect back to the cart page
header("Location: menu.php"); // Change this to the actual path of your cart page
exit;
?>
