<?php
class Supplier
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }
    //C
    public function addSupplier($name, $contact_info, $postal_address)
    {
        $stmt = $this->pdo->prepare("INSERT INTO suppliers (name, contact_info, postal_address) VALUES (?, ?, ?)");
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
    public function updateSupplier($id, $name, $contact_info, $postal_address)
    {
        $stmt = $this->pdo->prepare("UPDATE suppliers SET name = ?, contact_info = ?, postal_address = ? WHERE id = ?");
        return $stmt->execute([$name, $contact_info, $postal_address, $id]);
    }
    //D
    public function deleteSupplier($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
