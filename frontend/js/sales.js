document.addEventListener("DOMContentLoaded", function () {
    const salesModal = document.getElementById("salesModal");
    const addsaleButton = document.getElementById("addSaleButton");
    const closeModalButton = document.querySelector(".close-button");
    const salesForm = document.getElementById("salesForm");

    const loggedInUserId =localStorage.getItem('loggedInUserId');
    document.getElementById('userIdSale').value = loggedInUserId;

  
    addsaleButton.addEventListener("click", function () {
      openModal("Add New sale");
    });
  
    closeModalButton.addEventListener("click", closeModal);
  
    function openModal(title) {
      document.getElementById("modalTitle").textContent = title;
      salesModal.style.display = "block";
    }
  
    function closeModal() {
      salesModal.style.display = "none";
      salesForm.reset();
    }
    window.deleteSale = function (id) {
      if (confirm("Are you sure you want to delete this sale item?")) {
        fetch(
          `http://localhost/StockManagementSystem/api/endpoints/sale.php?id=${id}`,
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
              loadSalesData();
            } else {
              console.error("Error deleting sale item:", data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    };
  
    document.getElementById("salesForm").addEventListener("submit", function (event) {
        event.preventDefault();
  
        const formData = new FormData(this);
        const saleId = document.getElementById("salesId").value;
  
        const url = saleId
          ? `http://localhost/StockManagementSystem/api/endpoints/sale.php?id=${saleId}`
          : "http://localhost/StockManagementSystem/api/endpoints/sale.php";
        const method = saleId ? "PUT" : "POST";
  const id =7;
        fetch(url, {
          method: method,
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify(Object.fromEntries(formData)),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              document.getElementById("salesModal").style.display = "none";
              loadSalesData();
            } else {
              console.error("Error saving sale data:", data.message);
              document.getElementById("salesModal").style.display = "none";
              loadSalesData();
            }
          })
          .catch((error) => console.error("Error:", error));
      });
  
    function loadSalesData() {
      fetch(`http://localhost/StockManagementSystem/api/endpoints/sale.php?user_id=${loggedInUserId}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          const salesTableBody = document.getElementById("salesTableBody");
          salesTableBody.innerHTML = "";
  
          data.data.forEach((sale) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                  <td>${sale.item_name}</td>
                  <td>${sale.quantity}</td>
                  <td>${sale.unit_price}</td>
                  <td>${sale.total_amount}</td>
                  <td>${sale.sale_date}</td>
                  <td>
                      <button class="button" onclick="deleteSale(${sale.sale_id})">Delete</button>
                  </td>
              `;
              salesTableBody.appendChild(row);
          });
        })
        .catch((error) => console.error("Error loading sales data:", error));
    }
  
    loadSalesData();
  });
  
