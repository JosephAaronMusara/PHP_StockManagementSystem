<?php
class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($data) {

        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE name = ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetch()) {
            return ['error' => 'Category already exists.'];
        }
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        if($stmt->execute([$data['name']])){
            return ['id'=>$this->pdo->lastInsertId(),'message' => 'Category added successfully.'];
        }
    }

    public function updateCategory($id, $data) {

        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE name = ?  AND id != ?");
        $stmt->execute([$data['name'],$id]);
        if ($stmt->fetch()) {
            return ['error' => 'Item already exists.'];
        }
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
         if($stmt->execute([$data['name'], $id])){
            return ['message' => 'Category updated successfully.'];
        }
        return ['error' => 'Failed to update Category.'];
    }

    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'Category deleted successfully.'];
        }
        return ['error' => 'Failed to delete Category.'];
    }
}
