<?php
session_start();
require_once "../config.php"; // Ensure you're connecting to the database properly

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure product_id is passed
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $customization = isset($_POST['customization']) ? $_POST['customization'] : null; // Optional customization

        // Check if the product already exists in the cart
        if (!isset($_SESSION['cart'][$product_id])) {
            // Add the product to the cart
            $_SESSION['cart'][$product_id] = array(
                'id' => $product_id,
                'name' => $_POST['product_name'] ?? '', // Use product name from the post
                'price' => $_POST['product_price'] ?? 0, // Use product price from the post
                'customization' => $customization // Add customization info if applicable
            );
        } else {
            // Optionally, you could update the quantity or handle duplicate logic here
        }
        echo json_encode(array('status' => 'success', 'message' => 'Item added to cart!'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No product ID provided.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?><?php
session_start();
require_once "../config.php";

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get product details from the request
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];

// Create an item array
$item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
);

// Add item to the cart
$_SESSION['cart'][] = $item;

// Return a response (optional)
echo json_encode(array('success' => true));
?>

