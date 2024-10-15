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
        $user_id = $_SESSION['user_id'];
        $query = "SELECT sales.id AS sale_id, customers.name AS customer_name, sales.total_amount, sales.created_at AS sale_date, users.username AS sold_by, stock_items.name AS item_name, sale_details.quantity, sale_details.unit_price
                    FROM stockmanagement.sales 
                    LEFT JOIN stockmanagement.sale_details ON sales.id = sale_details.sale_id
                    LEFT JOIN stockmanagement.users ON sales.user_id = users.id 
                    LEFT JOIN stockmanagement.stock_items ON sale_details.stock_item_id = stock_items.id
                    LEFT JOIN stockmanagement.customers ON sales.customer_id = customers.id
                    WHERE sales.user_id=? ORDER BY sales.id DESC;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSaleById($id)
    {
        $query = "SELECT sales.id AS sale_id, sales.total_amount, sales.created_at AS sale_date, users.username AS sold_by, stock_items.name AS item_name, sale_details.quantity, sale_details.unit_price
                  FROM stockmanagement.sales 
                  LEFT JOIN stockmanagement.sale_details ON sales.id = sale_details.sale_id
                  LEFT JOIN stockmanagement.users ON sales.user_id = users.id 
                  LEFT JOIN stockmanagement.stock_items ON sale_details.stock_item_id = stock_items.id 
                  WHERE sales.id=?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSale($data)
    {
        if (!isset($data['user_id'], $data['total_amount'], $data['stock_item_id'], $data['quantity'], $data['unit_price'])) {
            return ['error' => 'Required fields are missing.'];
        }

        $stmt = $this->pdo->prepare("SELECT quantity FROM stock_items WHERE id=?");
        $stmt->execute([$data['stock_item_id']]);
        $stock_count = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stock_count) {
            return ['error' => 'Stock item not found.'];
        }

        $current_stock = (int)$stock_count['quantity'];

        if (isset($data['customer_id'])) {
            $finSttmnt = "INSERT INTO sales (user_id, total_amount, customer_id) VALUES (?, ?, ?)";
            $finData = [$data['user_id'], $data['total_amount'], $data['customer_id']];
        } else {
            $finSttmnt = "INSERT INTO sales (user_id, total_amount) VALUES (?, ?)";
            $finData = [$data['user_id'], $data['total_amount']];
        }

        if ($data['quantity'] > $current_stock || $data['quantity'] < 1) {
            return ['error' => 'Selected quantity is greater than the current stock or negative.'];
        }

        $stmt = $this->pdo->prepare($finSttmnt);
        if (!$stmt->execute($finData)) {
            return ['error' => 'Failed to record the sale.'];
        }

        $saleId = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare("INSERT INTO sale_details (sale_id, stock_item_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        if (!$stmt->execute([$saleId, $data['stock_item_id'], $data['quantity'], $data['unit_price']])) {
            return ['SaleDetailsId' => $saleId, 'error' => 'Failed to record sale details.'];
        }

        $stmt1 = $this->pdo->prepare("INSERT INTO transactions (transaction_type, stock_item_id, user_id, quantity) VALUES (?, ?, ?, ?)");
        if (!$stmt1->execute(['sale', $data['stock_item_id'], $data['user_id'], $data['quantity']])) {
            return ['SaleDetailsId' => $saleId, 'error' => 'Failed to record the transaction.'];
        }

        $final_stock = $current_stock - (int)$data['quantity'];
        $stmt = $this->pdo->prepare("UPDATE stock_items SET quantity = ? WHERE id = ?");
        if (!$stmt->execute([$final_stock, $data['stock_item_id']])) {
            return ['error' => 'Failed to update stock quantity.'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO stock_movements (stock_item_id, movement_type, quantity, user_id) VALUES (?, ?, ?, ?)");
        if (!$stmt->execute([$data['stock_item_id'], 'removal', $data['quantity'], $data['user_id']])) {
            return ['error' => 'Failed to record stock movement.'];
        }

        return ['id' => $saleId, 'message' => 'Sale recorded successfully.'];
    }

    public function deleteSale($id)
    {
        $user_id = $_SESSION['user_id'];
        $stmt2 = $this->pdo->prepare("SELECT quantity,stock_item_id FROM sale_details WHERE sale_id=?");
        $stmt2->execute([$id]);
        $stock_detail = $stmt2->fetch(PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare("DELETE FROM sales WHERE id = ?");
        if ($stmt->execute([$id])) {
            $stmt1 = $this->pdo->prepare("SELECT quantity FROM stock_items WHERE id=?");
            $stmt1->execute([$stock_detail['stock_item_id']]);
            $stock_count = $stmt1->fetch(PDO::FETCH_ASSOC);
            $current_stock = (int)$stock_count['quantity'];
            $new_stock = $current_stock + (int)$stock_detail['quantity'];

            $stmt3 = $this->pdo->prepare("UPDATE stock_items SET quantity = ? WHERE id = ?");
            if ($stmt3->execute([$new_stock, $stock_detail['stock_item_id']])) {
                $stmt4 = $this->pdo->prepare("INSERT INTO stock_movements (stock_item_id, movement_type, quantity, user_id) 
                VALUES (?, ?, ?, ?)");
                if ($stmt4->execute([$stock_detail['stock_item_id'], 'addition', $stock_detail['quantity'], $user_id])) {
                    return ['id' => $this->pdo->lastInsertId(), 'message' => 'Stock Movement added successfully.'];
                }
                return ['message' => 'Item updated successfully.'];
            }
            //
            return ['message' => 'Sale deleted successfully.', 'success'=>true];
        }
        return ['error' => 'Failed to delete sale.'];
    }

    public function getItemDetails($itemId)
    {
        $query = "SELECT id, name, selling_price, purchase_price FROM stock_items WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllStockItems()
    {
        $query = "SELECT id, name, selling_price FROM stock_items";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomers(){
        $query = "SELECT * FROM customers";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
