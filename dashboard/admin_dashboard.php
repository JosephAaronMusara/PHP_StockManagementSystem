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
                <li><a href="#overview" class="nav-link active">Overview</a></li>
                <li><a href="#manage-stock" class="nav-link">Manage Stock</a></li>
                <li><a href="#sales-n-purchases" class="nav-link">Sales & Purchases</a></li>
                <li><a href="#reports" class="nav-link">Reports</a></li>
                <li><a href="#settings" class="nav-link">Settings</a></li>
            </ul>
        </nav>

        <main class="dashboard-content">
            <section class="content-section active-section" id="overview">
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

            <section class="content-section" id="manage-stock">
                <h2>Manage Stock</h2>
            </section>

            <section id="sales-n-purchases" class="content-section">
                <h2>Sales & Purchases</h2>
            </section>

            <section id="reports" class="content-section">
                <h2>Reports</h2>
             
            </section>

            <section id="settings" class="content-section">
                <h2>Settings</h2>
               
            </section>
        </main>
    </div>
    <script src="../js/adminscript.js"></script>

</body>

</html>
