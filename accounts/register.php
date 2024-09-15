<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - REGISTER</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="auth-container">
        <form id="auth-form" class="auth-form" action="../includes/createuser.inc.php" method="POST">
            <h2>Create Account</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input id="pwd" type="password" name="pwd" placeholder="Password" required>
            <input id="confirm_pwd" type="password" name="confirm_pwd" placeholder="Confirm Password" required>
            
            <button id="registerBtn" type="submit" >Register</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
