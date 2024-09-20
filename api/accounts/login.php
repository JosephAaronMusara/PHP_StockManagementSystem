<?php
require '../core/Database.php';
require '../core/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        echo json_encode(["message" => "Login successful."]);
    } else {
        echo json_encode(["message" => "Invalid credentials."]);
    }
}
?>
