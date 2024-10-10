<?php
class StockItem
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }
    //C
    public function addItem($data)
    {
        $user_id = $_SESSION['user_id'];
        $stmt = $this->pdo->prepare("SELECT * FROM stock_items WHERE name = ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetch()) {
            $stmt = $this->pdo->prepare("UPDATE stock_items SET supplier_id = ?, quantity = quantity + ? WHERE name = ?");
            if ($stmt->execute([$data['supplier_id'], $data['quantity'], $data['name']])) {
                $stmt1 = $this->pdo->prepare("INSERT INTO transactions (transaction_type, stock_item_id,user_id, quantity) 
                VALUES (?, ?, ?, ?)");

                if($stmt1->execute(['purchase', $data['stock_item_id'], $data['user_id'],$data['quantity']])){
                return ['id' => $this->pdo->lastInsertId(), 'message' => 'Transaction added successfully.'];
                }
                return ['success' => true, 'message' => 'Stock quantity updated successfully.'];
            }
            return ['error' => 'Failed to update stock quantity.'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO stock_items (name, category_id, supplier_id, purchase_price, selling_price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$data['name'], $data['category_id'], $data['supplier_id'], $data['purchase_price'], $data['selling_price'], $data['quantity']])) {
            $stmt = $this->pdo->prepare("INSERT INTO stock_movements (stock_item_id, movement_type, quantity, user_id) 
            VALUES (?, ?, ?, ?)");
                if($stmt->execute([$this->pdo->lastInsertId(), 'addition', $data['quantity'], $user_id])){
                return ['id' => $this->pdo->lastInsertId(), 'message' => 'Stock Movement added successfully.'];
                }
            return ['id' => $this->pdo->lastInsertId(), 'message' => 'Item added successfully.'];
        }
        return ['error' => 'Failed to add item.'];
    }
    //R
    public function getAllStockItems()
    {
        $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
            LEFT JOIN categories ON stock_items.category_id = categories.id 
            LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //R
    public function getStockItemById($id)
    {
        $query = "SELECT stock_items.*, categories.name AS category_name, suppliers.name AS supplier_name FROM stock_items 
            LEFT JOIN categories ON stock_items.category_id = categories.id 
            LEFT JOIN suppliers ON stock_items.supplier_id = suppliers.id
            WHERE stock_items.id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //U
    public function updateItem($id, $data)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM stock_items WHERE name = ?  AND id != ?");
        $stmt->execute([$data['name'],$id]);
        if ($stmt->fetch()) {
            return ['error' => 'Item already exists.'];
        }
        $stmt = $this->pdo->prepare("UPDATE stock_items SET name = ?, category_id = ?, supplier_id = ?, purchase_price = ?, selling_price = ?, quantity = ? WHERE id = ?");
        if ($stmt->execute([$data['name'], $data['category_id'], $data['supplier_id'], $data['purchase_price'], $data['selling_price'], $data['quantity'], $id])) {
            return ['message' => 'Item updated successfully.'];
        }
        return ['error' => 'Failed to update item.'];
    }
    //D
    public function deleteItem($id)
    {

        $stmt = $this->pdo->prepare("DELETE FROM stock_items WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Item deleted successfully.'];
        }
        return ['error' => 'Failed to delete item.'];
    }

    public function getAllSuppiers()
    {
        $query = "SELECT id, name FROM suppliers";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStockCategories()
    {
        $query = "SELECT id, name FROM categories";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockValue(){
        $query = "SELECT SUM(purchase_price * quantity) AS total_stock_value FROM stock_items";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);

        $query2 = "SELECT COUNT(*) AS total_transactions FROM transactions";
        $stmt2 = $this->pdo->prepare($query2);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        return ['success' => true, 
                'total_stock_value' => $result1['total_stock_value'], 
                'total_transactions' => $result2['total_transactions']];

    }

}
