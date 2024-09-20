<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

$db = new Database();
$stockItem = new StockItem($db->getConnection());

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $item = $stockItem->getStockItemById($id);

    if ($item) {
        echo json_encode($item);
    } else {
        echo json_encode(["message" => "Stock item not found."]);
    }
} else {
    $items = $stockItem->getAllStockItems();
    echo json_encode($items);
}
