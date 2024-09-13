document.addEventListener("DOMContentLoaded", function () {
    const stockModal = document.getElementById("stockModal");
    const addStockButton = document.getElementById("addStockButton");
    const closeModalButton = document.querySelector(".close-button");
    const stockForm = document.getElementById("stockForm");
    const stockTableBody = document.getElementById("stockTableBody");

    // Event listeners
    addStockButton.addEventListener("click", function () {
        openModal("Add New Stock");
    });

    closeModalButton.addEventListener("click", closeModal);

    stockForm.addEventListener("submit", function (event) {
        event.preventDefault();
        saveStock();
    });

    // Open the modal with the given title
    function openModal(title) {
        document.getElementById("modalTitle").textContent = title;
        stockModal.style.display = "block";
    }

    // Close the modal
    function closeModal() {
        stockModal.style.display = "none";
        stockForm.reset();
    }

    // Save stock - either add or update
    function saveStock() {
        const stockId = document.getElementById("stockId").value;
        const itemName = document.getElementById("itemName").value;
        const category = document.getElementById("category").value;
        const quantity = document.getElementById("quantity").value;
        const purchasePrice = document.getElementById("purchasePrice").value;
        const sellingPrice = document.getElementById("sellingPrice").value;
        const supplier = document.getElementById("supplier").value;

        if (stockId) {
            console.log(`Updating stock ID ${stockId}`);
        } else {
            console.log("Adding new stock");
        }

        closeModal();
    }

    // Make editStock a global function
    window.editStock = function (stockId) {
        const stock = stockData.find(item => item.id === stockId); // Fetch the stock item data by ID
        
        if (stock) {
            document.getElementById("stockId").value = stock.id;
            document.getElementById("itemName").value = stock.itemName;
            document.getElementById("category").value = stock.category;
            document.getElementById("quantity").value = stock.quantity;
            document.getElementById("purchasePrice").value = stock.purchasePrice;
            document.getElementById("sellingPrice").value = stock.sellingPrice;
            document.getElementById("supplier").value = stock.supplier;

            openModal("Edit Stock");
        }
    }

    // Make deleteStock a global function
    window.deleteStock = function (stockId) {
        console.log(`Deleting stock ID ${stockId}`);
        // Add logic to communicate with backend to delete the stock item
    }

    // Example stock data - replace with actual backend data fetching
    const stockData = [
        { id: 1, itemName: "Item 1", category: "Category 1", quantity: 50, purchasePrice: 100, sellingPrice: 150, supplier: "Supplier 1" },
        { id: 2, itemName: "Item 2", category: "Category 2", quantity: 30, purchasePrice: 200, sellingPrice: 300, supplier: "Supplier 2" }
    ];

    // Load stock data
    function loadStockData() {
        stockTableBody.innerHTML = ""; // Clear previous data
        stockData.forEach(stock => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${stock.itemName}</td>
                <td>${stock.category}</td>
                <td>${stock.quantity}</td>
                <td>${stock.purchasePrice}</td>
                <td>${stock.sellingPrice}</td>
                <td>${stock.supplier}</td>
                <td>
                    <button class="button" onclick="editStock(${stock.id})">Edit</button>
                    <button class="button" onclick="deleteStock(${stock.id})">Delete</button>
                </td>
            `;
            stockTableBody.appendChild(row);
        });
    }

    loadStockData();
});
