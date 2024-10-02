<?php
class Transaction {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllTransactions() {
        $user_id = $_SESSION['user_id'];
        $query ="SELECT transactions.*, stock_items.name AS item_name, users.username AS cashier FROM transactions
        LEFT JOIN stock_items ON transactions.stock_item_id = stock_items.id
        LEFT JOIN users ON transactions.user_id=users.id
        WHERE user_id=?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addTransaction($data){
        $stmt = $this->pdo->prepare("INSERT INTO transactions (transaction_type, stock_item_id, transaction_date, user_id, quantity) 
                                    VALUES (?, ?, ?, ?, ?)");
         if($stmt->execute([$data['transaction_type'], $data['stock_item_id'], $data['transaction_date'], $data['user_id'],$data['quantity']])){
            return ['id' => $this->pdo->lastInsertId(), 'message' => 'Transaction added successfully.'];
         }
         return ['error' => 'Transaction not Added.'];

    }

    public function deleteTransaction($id) {
        $stmt = $this->pdo->prepare("DELETE FROM transactions WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
