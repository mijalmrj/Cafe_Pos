<?php
// Include database connection
include '../config.php'; // Make sure this file contains the correct connection info

// Check if the request is a POST request and has valid order data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the JSON data sent from the frontend
    $orderData = json_decode(file_get_contents("php://input"), true);

    // Prepare to insert each item into the order_detail table
    if (!empty($orderData['items'])) {
        $orderNumber = $orderData['orderNumber'];
        $totalAmount = $orderData['totalAmount'];

        // Insert each item in the cart into the database
        foreach ($orderData['items'] as $item) {
            $orderType = $item['name']; // Assuming 'name' is the order type (e.g., coffee, latte)
            $size = $item['size']; // Assuming 'size' exists in the cart items
            $customFlavor = $item['sugarText']; // Assuming this holds the flavor/sweetness info

            // Insert into the order_detail table
            $query = "INSERT INTO order_detail (order_type, size, Customize_flavor) VALUES (?, ?, ?, ?)";

            // Prepare and execute the query
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssis", $orderType, $size, $customFlavor);

            if ($stmt->execute()) {
                echo "Order item saved successfully.";
            } else {
                echo "Error saving order item: " . $stmt->error;
            }
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "No order items found.";
    }
} else {
    echo "Invalid request.";
}
?>
