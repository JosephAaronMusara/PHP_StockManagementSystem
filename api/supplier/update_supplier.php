<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/Supplier.php';

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    //parse_str(file_get_contents("php://input"), $_PUT);

    $db = new Database();
    $supplier = new Supplier($db->getConnection());

    // $id = $_PUT['id'];
    // $name = $_PUT['name'];
    // $contact_info = $_PUT['contact_info'];
    // $postal_address = $_PUT['postal_address'];

        $data = json_decode(file_get_contents("php://input"));

        $id = $data->id;
        $name = $data->name;
        $contact_info = $data->contact_info;
        $postal_address = $data->postal_address;

    if ($supplier->updateSupplier($id, $name, $contact_info, $postal_address)) {
        echo json_encode(["message" => "Supplier updated successfully."]);
    } else {
        echo json_encode(["message" => "Error updating supplier."]);
    }
}
