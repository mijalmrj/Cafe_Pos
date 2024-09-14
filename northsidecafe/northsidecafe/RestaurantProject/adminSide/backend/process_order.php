<?php
// fetch_menu.php
include '../config.php';

// Fetch POST data from form submission
$item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
$item_size = isset($_POST['item_size']) ? $_POST['item_size'] : '';
$milk_type = isset($_POST['milk_type']) ? $_POST['milk_type'] : '';
$takeaway = isset($_POST['takeaway']) ? 1 : 0;
$sugar = isset($_POST['sugar']) ? 1 : 0;
$cost = isset($_POST['cost']) ? floatval($_POST['cost']) : 0.00;

// Insert order into database
$sql = "INSERT INTO orders (item_name, item_size, milk_type, takeaway, sugar, cost) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "sssidd", $item_name, $item_size, $milk_type, $takeaway, $sugar, $cost);

if (mysqli_stmt_execute($stmt)) {
    echo "Order placed successfully!";
} else {
    echo "Error: " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>
