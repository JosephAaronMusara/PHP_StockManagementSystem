<?php
class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function register($data) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$data['username'], $data['email']]);
        if ($stmt->fetch()) {
            return ['error' => 'Username or email already exists.'];
        }
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, pwd) VALUES (?, ?, ?)");
         if($stmt->execute([$data['username'], $data['email'], $passwordHash])){
            return ['id' => $this->pdo->lastInsertId(), 'message' => 'User created successfully.'];
         }
         return ['error' => 'Failed to create user.'];
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pwd'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }

        return false;
    }

    public function deleteUser($id){
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            return ['message' => 'User deleted successfully.'];
        }
        return ['error' => 'Failed to delete user.'];
    }

    public function logout() {
        session_start();
        session_destroy();
        return true;
    }
}
?>
