<?php
    $username = "Uncle";
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - ADMIN</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Stock Management - ADMIN</h1>
            <div class="user-info">
                <span>Welcome, <?php echo $username ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </header>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="#">Overview</a></li>
                <li><a href="#">Manage Stock</a></li>
                <li><a href="#">Sales & Purchases</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </nav>
        <main class="dashboard-content">
            <section class="overview">
                <h2>Overview</h2>
                <div class="stats">
                    <div class="stat-item">
                        <p>Total Stock Value</p>
                        <h3>$100,000</h3>
                    </div>
                    <div class="stat-item">
                        <p>Recent Transactions</p>
                        <h3>15</h3>
                    </div>
                    <div class="stat-item">
                        <p>Low Stock Alerts</p>
                        <h3>3</h3>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
</html>
