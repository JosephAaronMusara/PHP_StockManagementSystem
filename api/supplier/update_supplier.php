<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/Supplier.php';

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT); // Parse the PUT request data

    $db = new Database();
    $supplier = new Supplier($db->getConnection());

    $id = $_PUT['id'];
    $name = $_PUT['name'];
    $contact_info = $_PUT['contact_info'];
    $postal_address = $_PUT['postal_address'];

    if ($supplier->updateSupplier($id, $name, $contact_info, $postal_address)) {
        echo json_encode(["message" => "Stock item updated successfully."]);
    } else {
        echo json_encode(["message" => "Error updating stock item."]);
    }
}
