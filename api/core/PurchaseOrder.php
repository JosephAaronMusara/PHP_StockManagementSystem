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
        $user_id = $_SESSION['user_id'];
        $query = "SELECT purchase_orders.*, suppliers.name AS supplier_name, purchase_order_details.*, users.username AS ordered_by, stock_items.name AS item_name
                  FROM stockmanagement.purchase_orders 
                  LEFT JOIN stockmanagement.purchase_order_details ON purchase_orders.id = purchase_order_details.purchase_order_id
                  LEFT JOIN stockmanagement.users ON purchase_orders.user_id = users.id 
                  LEFT JOIN stockmanagement.suppliers ON purchase_orders.supplier_id = suppliers.id
                  LEFT JOIN stockmanagement.stock_items ON purchase_order_details.stock_item_id = stock_items.id
                  WHERE purchase_orders.user_id=?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPurchaseOrderById($id)
    {
        $query =  "SELECT purchase_orders.*, suppliers.name AS supplier_name, purchase_order_details.*, users.username AS ordered_by, stock_items.name AS item_name
        FROM stockmanagement.purchase_orders 
        LEFT JOIN stockmanagement.purchase_order_details ON purchase_orders.id = purchase_order_details.purchase_order_id
        LEFT JOIN stockmanagement.users ON purchase_orders.user_id = users.id 
        LEFT JOIN stockmanagement.suppliers ON purchase_orders.supplier_id = suppliers.id
        LEFT JOIN stockmanagement.stock_items ON purchase_order_details.stock_item_id = stock_items.id
        WHERE purchase_orders.id = ?";
        $stmt = $this->pdo->prepare($query);
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
        $stmt = $this->pdo->prepare("DELETE FROM purchase_order_details WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
