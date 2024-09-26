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
        $query = "SELECT sales.id AS sale_id, sales.total_amount, sales.created_at AS sale_date, users.username AS sold_by, stock_items.name AS item_name, sale_details.quantity, sale_details.unit_price
                    FROM stockmanagement.sales LEFT JOIN stockmanagement.sale_details ON sales.id = sale_details.sale_id
                    LEFT JOIN stockmanagement.users ON sales.user_id = users.id LEFT JOIN stockmanagement.stock_items ON sale_details.stock_item_id = stock_items.id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSaleById($id)
    {


        $query = "SELECT sales.id AS sale_id, sales.total_amount, sales.created_at AS sale_date, users.username AS sold_by, stock_items.name AS item_name, sale_details.quantity, sale_details.unit_price
        FROM stockmanagement.sales LEFT JOIN stockmanagement.sale_details ON sales.id = sale_details.sale_id
        LEFT JOIN stockmanagement.users ON sales.user_id = users.id LEFT JOIN stockmanagement.stock_items ON sale_details.stock_item_id = stock_items.id WHERE sales.id=?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSale($data)
    {

        $stmt = $this->pdo->prepare("INSERT INTO sales (user_id, total_amount) 
                                    VALUES (?, ?)");
        if ($stmt->execute([$data['user_id'], $data['total_amount']])) {
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
