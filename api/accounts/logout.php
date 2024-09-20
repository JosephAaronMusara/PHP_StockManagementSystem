<?php
require '../core/User.php';
$user = new User();
if ($user->logout()) {
    echo json_encode(["message" => "Logged out successfully."]);
}
?>
