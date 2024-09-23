<?php
class Sale
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllSales()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sales");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSaleById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSale($data)
    {

        $stmt = $this->pdo->prepare("INSERT INTO sales (user_id, total_amount) 
                                    VALUES (?, ?)");
        if ($stmt->execute([$data['user_id'],$data['total_amount']])) {
            $stmt = $this->pdo->prepare("INSERT INTO sale_details (sale_id, stock_item_id, quantity, unit_price) 
            VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$this->pdo->lastInsertId(), $data['stock_item_id'], $data['quantity'], $data['unit_price']])) {
                return ['SaleDetailsId' => $this->pdo->lastInsertId(), 'message' => 'Sale added successfully.'];
            }
        }
        return ['error' => 'Failed to record the sale.'];
    }

    public function deleteSale($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sales WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
