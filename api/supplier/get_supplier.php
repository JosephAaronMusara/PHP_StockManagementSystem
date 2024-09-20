<?php
require '../includes/headers.php';
require '../includes/config.php';
require '../core/Database.php';
require '../core/Supplier.php';

$db = new Database();
$supplier = new Supplier($db->getConnection());

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $supp = $supplier->getSupplierById($id);

    if ($supp) {
        echo json_encode($supp);
    } else {
        echo json_encode(["message" => "Supplier not found."]);
    }
} else {
    $supp = $supplier->getAllSuppliers();
    echo json_encode($supp);
}
