<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

// Initialize the Database and StockItem class
$db = new Database();
$stockItem = new StockItem($db->getConnection());

// Fetch all stock items
$items = $stockItem->getAllStockItems();

// Return the result as JSON
echo json_encode($items);
