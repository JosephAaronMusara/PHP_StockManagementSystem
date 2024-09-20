<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/Supplier.php';


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $supplier = new Supplier($db->getConnection());

    // $id = $_GET['id'];
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;


    if ($supplier->deleteSupplier($id)) {
        echo json_encode(["message" => "Supplier deleted successfully."]);
    } else {
        echo json_encode(["message" => "Error deleting Supplier."]);
    }
// }
