<?php
class PurchaseOrder
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllPurchaseOrders()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM purchase_orders");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPurchaseOrderById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM purchase_orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addPurchaseOrder($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO purchase_orders (supplier_id, user_id, total_amount, received_at) 
                                    VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$data['supplier_id'], $data['user_id'], $data['total_amount'], $data['received_at']])) {

            $stmt = $this->pdo->prepare("INSERT INTO purchase_order_details (purchase_order_id, stock_item_id, quantity, unit_price) 
                                        VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$this->pdo->lastInsertId(), $data['stock_item_id'], $data['quantity'], $data['unit_price']])) {
                return ['PODetailsId' => $this->pdo->lastInsertId(), 'message' => 'PODetails added successfully.'];
            }
        }
        return ['error' => 'Failed to add record.'];
    }


    public function deletePurchaseOrder($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM purchase_orders WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
