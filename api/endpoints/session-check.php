<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['logged_in' => false, 'message' => 'User not logged in']);
    exit;
}

$role = $_SESSION['role'];
echo json_encode(['logged_in' => true, 'role' => $role]);
?>
