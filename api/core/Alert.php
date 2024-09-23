<?php
class Alert {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllAlerts() {
        $stmt = $this->pdo->prepare("SELECT * FROM alerts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlertById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alerts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAlert($data, $alert_type, $message, $created_at) {
        $stmt = $this->pdo->prepare("INSERT INTO alerts (user_id,stock_item_id, alert_type, message) 
                                    VALUES (?, ?, ?, ?)");
        if($stmt->execute([$data['user_id'],$data['stock_item_id'], $data['alert_type'], $data['message']])){
            return ['id' => $this->pdo->lastInsertId(), 'message' => 'Alert added successfully.'];
        }
        return ['error' => 'Failed to add Alert.'];
    }

    public function deleteAlert($id) {
        $stmt = $this->pdo->prepare("DELETE FROM alerts WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
}
