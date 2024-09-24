<?php
class StockItem {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    //C
    public function addItem($data) {

        $stmt = $this->pdo->prepare("SELECT * FROM stock_items WHERE name = ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetch()) {
            return ['error' => 'Item already exists.'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO stock_items (name, category_id, supplier_id, purchase_price, selling_price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        if($stmt->execute([$data['name'], $data['category_id'], $data['supplier_id'], $data['purchase_price'], $data['selling_price'], $data['quantity']])){
         return ['id' => $this->pdo->lastInsertId(), 'message' => 'Item added successfully.'];
        }
        return ['error' => 'Failed to add item.'];

    }
    // //R
    // public function getItems($category_id = null) {
    //     $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
    //               LEFT JOIN categories ON stock_items.category_id = categories.id 
    //               LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id";

    //     if ($category_id) {
    //         $query .= " WHERE category_id = ?";
    //         $stmt = $this->pdo->prepare($query);
    //         $stmt->execute([$category_id]);
    //     } else {
    //         $stmt = $this->pdo->prepare($query);
    //         $stmt->execute();
    //     }

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
        //R
        public function getAllStockItems() {
            $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
            LEFT JOIN categories ON stock_items.category_id = categories.id 
            LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        //R
        public function getStockItemById($id) {
            $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
            LEFT JOIN categories ON stock_items.category_id = categories.id 
            LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id
            WHERE stock_items.id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    //U
    public function updateItem($id, $data) {

        $stmt = $this->pdo->prepare("SELECT * FROM stock_items WHERE name = ?  AND id != ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetch()) {
            return ['error' => 'Item already exists.'];
        }
        $stmt = $this->pdo->prepare("UPDATE stock_items SET name = ?, category_id = ?, supplier_id = ?, purchase_price = ?, selling_price = ?, quantity = ? WHERE id = ?");
         if($stmt->execute([$data['name'], $data['category_id'], $data['supplier_id'], $data['purchase_price'], $data['selling_price'], $data['quantity'], $id])){
            return ['message' => 'Item updated successfully.'];
        }
        return ['error' => 'Failed to update item.'];
    }
    //D
    public function deleteItem($id) {

        $stmt = $this->pdo->prepare("DELETE FROM stock_items WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }
    
}
