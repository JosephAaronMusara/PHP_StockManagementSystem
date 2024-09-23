<?php
class Supplier
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }
    //C
    public function addSupplier($data)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM suppliers WHERE name = ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetch()) {
            return ['error' => 'Supplier already exists.'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO suppliers (name, contact_info, postal_address) VALUES (?, ?, ?)");
        if($stmt->execute([$data['name'], $data['contact_info'], $data['postal_address']])){
         return ['id' => $this->pdo->lastInsertId(), 'message' => 'Supplier added successfully.'];
        }
        return ['error' => 'Failed to add Supplier.'];

        return $stmt->execute([$name, $contact_info, $postal_address]);
    }

    //R
    public function getAllSuppliers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //R
    public function getSupplierById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //U
    public function updateSupplier($id, $data)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers WHERE name = ?  AND id != ?");
        $stmt->execute([$data['name'],$id]);
        if ($stmt->fetch()) {
            return ['error' => 'Item already exists.'];
        }
        $stmt = $this->pdo->prepare("UPDATE suppliers SET name = ?, contact_info = ?, postal_address = ? WHERE id = ?");
         if($stmt->execute([$data['name'], $data['contact_info'], $data['supplier_id'], $data['postal_address'], $id])){
            return ['message' => 'Supplier updated successfully.'];
        }
        return ['error' => 'Failed to update Supplier.'];
    }
    //D
    public function deleteSupplier($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Supplier deleted successfully.'];
        }
        return ['error' => 'Failed to delete supplier.'];
    }
}

