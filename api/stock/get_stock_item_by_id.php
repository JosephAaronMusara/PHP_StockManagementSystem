<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

// Initialize the Database and StockItem class
$db = new Database();
$stockItem = new StockItem($db->getConnection());

// Get the stock item ID from the URL or request (e.g., ?id=1)
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["message" => "No ID provided"]));

// Fetch the stock item by ID
$item = $stockItem->getStockItemById($id);

// If the item exists, return it as JSON
if ($item) {
    echo json_encode($item);
} else {
    echo json_encode(["message" => "Stock item not found."]);
}
