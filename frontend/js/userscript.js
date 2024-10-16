document.addEventListener("DOMContentLoaded", function () {
  const stockModal = document.getElementById("stockModal");
  const addStockButton = document.getElementById("addStockButton");
  const closeModalButton = document.querySelector(".close-button");
  const stockForm = document.getElementById("stockForm");
  const stockTableBody = document.getElementById("stockTableBody");

  const loggedInUsername = localStorage.getItem('loggedInUsername');
  document.getElementById('welcomeSpan').innerText = `Hello ${loggedInUsername.toUpperCase()}`;
  const loggedInUserId =localStorage.getItem('loggedInUserId');

  document.getElementById("logout-btn").addEventListener("click", function () {
    const isConfirmed = confirm("Are you sure you want to log out?");
    if (isConfirmed) {
      localStorage.removeItem("loggedInUsername");
      localStorage.removeItem("loggedInUserId");
      localStorage.removeItem("isActive");
      window.location.href = "../../api/core/logout.php"
    }
  });

  axios.get('http://localhost/StockManagementSystem/api/endpoints/stock.php?fetch_items=true')
        .then(data => {
            if (data.data.success) {
                const itemDropdown = document.getElementById('supplier');
                itemDropdown.innerHTML = '';
                const categoryDropdown = document.getElementById('category');
                categoryDropdown.innerHTML = '';
                const supOption = document.createElement('option');
                supOption.value=null;
                supOption.textContent = 'Select Supplier :';
                itemDropdown.appendChild(supOption);  

                const categoryOption = document.createElement('option');
                categoryOption.value=null;
                categoryOption.textContent = 'Select Category :';
                categoryDropdown.appendChild(categoryOption);  
                data.data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    itemDropdown.appendChild(option);
                });
                data.data.data1.forEach(category => {
                  const option = document.createElement('option');
                  option.value = category.id;
                  option.textContent = category.name;
                  categoryDropdown.appendChild(option);
              });
            } else {
                console.error("Error fetching items:", data.data.message);
            }
        })
        .catch(error => console.error("Error:", error));


  addStockButton.addEventListener("click", function () {
    openModal("Add New Stock");
  });

  closeModalButton.addEventListener("click", closeModal);

  function openModal(title) {
    document.getElementById("modalTitle").textContent = title;
    stockModal.style.display = "block";
  }

  function closeModal() {
    stockModal.style.display = "none";
    stockForm.reset();
  }
  window.deleteStock = function (id) {
    if (confirm("Are you sure you want to delete this stock item?")) {
      fetch(
        `http://localhost/StockManagementSystem/api/endpoints/stock.php?id=${id}`,
        {
          method: "DELETE",
          headers: {
            Accept: "application/json",
          },
        }
      )
        .then((response) => response.json())
        .then((data) => {
            console.log(data['message']);
          if (data.success) {
            loadStockItems();
          } else {
            console.error("Error deleting stock item:", data.message);
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  };

  window.editStock = function (id) {
    axios.get(`http://localhost/StockManagementSystem/api/endpoints/stock.php?id=${id}`)
      .then((data) => {
        const item = data.data.data;
        document.getElementById("stockId").value = item.id;
        document.getElementById("itemName").value = item.name;
        document.getElementById("category").value = item.category_id;
        document.getElementById("quantity").value = item.quantity;
        document.getElementById("purchasePrice").value = item.purchase_price;
        document.getElementById("sellingPrice").value = item.selling_price;
        document.getElementById("supplier").value = item.supplier_id;

        document.getElementById("modalTitle").textContent = "Edit Stock";
        document.getElementById("stockModal").style.display = "block";
      })
      .catch((error) => console.error("Error fetching stock item:", error));
  };

  document.getElementById("stockForm").addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(this);
      const stockId = document.getElementById("stockId").value;

      const url = stockId
        ? `http://localhost/StockManagementSystem/api/endpoints/stock.php?id=${stockId}`
        : "http://localhost/StockManagementSystem/api/endpoints/stock.php";

      const data = Object.fromEntries(formData);

      const request = stockId 
      ? axios.put(url, data, {
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        })
      : axios.post(url, data, {
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        });
  
        request
        .then((response) => {
          if (response.data.success) {
            document.getElementById("stockModal").style.display = "none";
            loadStockItems();
          } else {
            console.error("Error saving stock item:", data.message);
            document.getElementById("stockModal").style.display = "none";
            loadStockItems();
          }
        })
        .catch((error) => console.error("Error:", error));
    });

  function loadStockItems() {
    axios.get("http://localhost/StockManagementSystem/api/endpoints/stock.php")
      .then((data) => {
        const stockTableBody = document.getElementById("stockTableBody");
        stockTableBody.innerHTML = "";

        data.data.data.forEach((item) => {
          const row = document.createElement("tr");
          row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.category_name}</td>
                <td>${item.quantity}</td>
                <td>${item.purchase_price}</td>
                <td>${item.selling_price}</td>
                <td>${item.supplier_name}</td>
                <td>
                    <button class="button" onclick="editStock(${item.id})">Edit</button>
                    <button class="button" onclick="deleteStock(${item.id})">Delete</button>
                </td>
            `;
          stockTableBody.appendChild(row);
        });
      })
      .catch((error) => console.error("Error loading stock items:", error));
  }

  loadStockItems();
});
