<?php
class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createUser($data) {
        if ($data['password'] !== $data['confirm_password']) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, role, pwd) VALUES (:username, :email, :role, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role',$data['role']);
        $stmt->bindParam(':password', $hashedPassword);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User created successfully.'];
        }
        return ['success' => false, 'message' => 'Failed to create user.'];
    }

    public function loginUser($data) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['pwd'])) {
            //session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return ['success' => true, 'message' => 'Login successful','role'=>$user['role'],'user_id'=>$user['id'],'username'=>$user['username']];
        }
        return ['success' => false, 'message' => 'Invalid username or password'];
    }

    public function logoutUser() {
        session_start();
        session_unset();
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
    
        public function updateUserDetails($id, $data) {
            $setFields = [];
            if (isset($data['username'])) {
                $setFields[] = "username = :username";
            }
            if (isset($data['email'])) {
                $setFields[] = "email = :email";
            }
            if (isset($data['password']) && $data['password'] === $data['confirm_password']) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $setFields[] = "pwd = :pwd";
            } elseif (isset($data['password']) && $data['password'] !== $data['confirm_password']) {
                return ['success' => false, 'message' => 'Passwords do not match.'];
            }
    
            if (empty($setFields)) {
                return ['success' => false, 'message' => 'No data to update.'];
            }
    
            $sql = "UPDATE users SET " . implode(', ', $setFields) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            if (isset($data['username'])) {
                $stmt->bindParam(':username', $data['username']);
            }
            if (isset($data['email'])) {
                $stmt->bindParam(':email', $data['email']);
            }
            if (isset($data['password'])) {
                $stmt->bindParam(':pwd', $hashedPassword);
            }
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'User details updated successfully.'];
            }
            return ['success' => false, 'message' => 'Failed to update user details.'];
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
