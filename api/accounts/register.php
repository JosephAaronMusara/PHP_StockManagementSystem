<?php
require '../includes/config.php';
require '../includes/headers.php';
require '../core/Database.php';
require '../core/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];

    if ($user->register($username, $email, $password)) {
        echo json_encode(["message" => "User registered successfully."]);
    } else {
        echo json_encode(["message" => "Error registering user."]);
    }
}
?>
