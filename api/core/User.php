<?php
class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$data['username'], $data['email']]);
        if ($stmt->fetch()) {
            return ['error' => 'Username or email already exists.'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, role, pwd) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$data['username'], $data['email'], $data['role'], password_hash($data['pwd'],PASSWORD_BCRYPT)])) {
            return ['id' => $this->pdo->lastInsertId(), 'message' => 'User created successfully.'];
        }
        return ['error' => 'Failed to create user.'];
    }

    public function updateUser($id, $data)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$data['username'], $data['email'], $id]);
        if ($stmt->fetch()) {
            return ['error' => 'Username or email already exists.'];
        }

        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, pwd = ?");
        if ($stmt->execute([$data['username'], $data['email'], password_hash($data['pwd'],PASSWORD_BCRYPT)])) {
            return ['message' => 'User updated successfully.'];
        }
        return ['error' => 'Failed to update user.'];
    }

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'User deleted successfully.'];
        }
        return ['error' => 'Failed to delete user.'];
    }

    public function logout()
    {
        session_start();
        session_destroy();
        return true;
    }
}
