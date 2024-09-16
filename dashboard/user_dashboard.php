<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../accounts/login.php");
    exit;
}
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Stock Management</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>User Stock Management</h1>
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['username'] ."!" ?></span>
                <a href="../accounts/logout.php">Logout</a>
            </div>
        </header>
        <main class="dashboard-content">
            <section class="stock-management">
                <h2>Manage Stock</h2>
                <button id="addStockButton" class="button">Add New Stock</button>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        <!-- .................... -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <div id="stockModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="modalTitle">Add New Stock</h2>
            <form id="stockForm">
                <input type="hidden" id="stockId">
                <input type="text" id="itemName" placeholder="Item Name" required>
                <input type="text" id="category" placeholder="Category" required>
                <input type="number" id="quantity" placeholder="Quantity" required>
                <input type="number" id="purchasePrice" placeholder="Purchase Price" required>
                <input type="number" id="sellingPrice" placeholder="Selling Price" required>
                <input type="text" id="supplier" placeholder="Supplier" required>
                <button type="submit" class="button">Save Stock</button>
            </form>
        </div>
    </div>

    <script src="../js/userscript.js"></script>
</body>
</html>
