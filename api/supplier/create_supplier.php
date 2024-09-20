<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/Supplier.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $supplier = new Supplier();
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $postal_address = $_POST['postal_address'];

// $data = json_decode(file_get_contents("php://input"));

// $name = $data->name;
// $contact_info = $data->contact_info;
// $postal_address = $data->postal_address;

if ($supplier->addSupplier($name, $contact_info, $postal_address)) {
    echo json_encode(["message" => "Supplier added successfully."]);
} else {
    echo json_encode(["message" => "Error adding supplier."]);
}
}
