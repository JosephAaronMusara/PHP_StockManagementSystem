<?php
session_start();
session_destroy();  // End session
header("Location: login.php");  // Redirect to login page
exit;
?>
