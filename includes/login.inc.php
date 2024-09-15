<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pwd'];

    require_once "dbh.inc.php";  // Include your DB connection

    // Prepare SQL and bind parameters
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user from database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['pwd'])) {
        // Password matches, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to dashboard or protected page
        header("Location: ../dashboard/user_dashboard.php");
        exit;
    } else {
        // Incorrect username or password
        echo "Invalid username or password";
    }
}
?>
