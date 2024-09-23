<?php
class StockMovement {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllStockMovements() {
        $stmt = $this->pdo->prepare("SELECT * FROM stock_movements");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockMovementById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM stock_movements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addStockMovement($data) {
        $stmt = $this->pdo->prepare("INSERT INTO stock_movements (stock_item_id, movement_type, quantity, user_id) 
                                    VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['stock_item_id'], $data['movement_type'], $data['quantity'], $data['user_id']]);
    }

    public function deleteStockMovement($id) {
        $stmt = $this->pdo->prepare("DELETE FROM stock_movements WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
