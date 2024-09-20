<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/StockItem.php';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $stockItem = new StockItem($db->getConnection());

    // $id = $_POST['id'];
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    if ($stockItem->deleteItem($id)) {
        echo json_encode(["message" => "Stock item deleted successfully."]);
    } else {
        echo json_encode(["message" => "Error deleting stock item."]);
    }
//}
