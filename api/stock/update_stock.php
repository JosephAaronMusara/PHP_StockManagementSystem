<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT); // Parse the PUT request data

    $db = new Database();
    $stockItem = new StockItem($db->getConnection());

    $id = $_PUT['id'];
    $name = $_PUT['name'];
    $category_id = $_PUT['category_id'];
    $supplier_id = $_PUT['supplier_id'];
    $purchase_price = $_PUT['purchase_price'];
    $selling_price = $_PUT['selling_price'];
    $quantity = $_PUT['quantity'];

    if ($stockItem->updateItem($id, $name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity)) {
        echo json_encode(["message" => "Stock item updated successfully."]);
    } else {
        echo json_encode(["message" => "Error updating stock item."]);
    }
}
