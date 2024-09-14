<?php
// fetch_menu.php
include '../config.php';

try {
    $sql = "SELECT item_id, item_name, item_type, item_category, item_price, item_description, item_image FROM Menu ORDER BY item_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output items as JSON
    header('Content-Type: application/json');
    echo json_encode($items);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching menu items: ' . $e->getMessage()]);
}
?>
