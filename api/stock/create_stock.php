<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stockItem = new StockItem();
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $purchase_price = $_POST['purchase_price'];
    $selling_price = $_POST['selling_price'];
    $quantity = $_POST['quantity'];

    if ($stockItem->addItem($name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity)) {
        echo json_encode(["message" => "Stock item added successfully."]);
    } else {
        echo json_encode(["message" => "Error adding stock item."]);
    }
}
