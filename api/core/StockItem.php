<?php
class StockItem {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    //C
    public function addItem($name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity) {
        $stmt = $this->pdo->prepare("INSERT INTO stock_items (name, category_id, supplier_id, purchase_price, selling_price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity]);
    }
    //R
    public function getItems($category_id = null) {
        $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
                  LEFT JOIN categories ON stock_items.category_id = categories.id 
                  LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id";

        if ($category_id) {
            $query .= " WHERE category_id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$category_id]);
        } else {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        //R
        public function getAllStockItems() {
            $stmt = $this->pdo->prepare("SELECT * FROM stock_items");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        //R
        public function getStockItemById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM stock_items WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    //U
    public function updateItem($id, $name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE stock_items SET name = ?, category_id = ?, supplier_id = ?, purchase_price = ?, selling_price = ?, quantity = ? WHERE id = ?");
        return $stmt->execute([$name, $category_id, $supplier_id, $purchase_price, $selling_price, $quantity, $id]);
    }
    //D
    public function deleteItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM stock_items WHERE id = ?");
        return $stmt->execute([$id]);
    }


}
