document.addEventListener("DOMContentLoaded", function () {
    const supplierModal = document.getElementById("supplierModal");
    const submitBtnSupplier = document.getElementById("submitBtnSupplier");
    const closeModalButton = document.getElementById("suppliermodalClose");
    const supplierForm = document.getElementById("supplierForm");
    const supplierTableBody = document.getElementById("supplierTableBody");
    const addSupplierButton = document.getElementById("addSupplierButton");
  
    const loggedInUsername = localStorage.getItem('loggedInUsername');
    document.getElementById('welcomeSpan').innerText = `Hello ${loggedInUsername.toUpperCase()}`;
    const loggedInUserId =localStorage.getItem('loggedInUserId');
  
    submitBtnSupplier.addEventListener("click", function () {
      openModal("Add New Supplier");
    });
  
    closeModalButton.addEventListener("click", closeModal);
  
    function openModal(title) {
      document.getElementById("modalTitleSupplier").textContent = title;
      supplierModal.style.display = "block";
    }
    addSupplierButton.addEventListener("click", function () {
        openModal("Add New Supplier");
      });
  
    function closeModal() {
      supplierModal.style.display = "none";
      supplierForm.reset();
    }
    window.deleteSupplier= function (id) {
      if (confirm("Are you sure you want to delete this supplier?")) {
        fetch(
          `http://localhost/StockManagementSystem/api/endpoints/supplier.php?id=${id}`,
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
              loadSuppliers();
            } else {
              console.error("Error deleting supplier:", data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    };
  
    window.editSupplier = function (id) {
      fetch(
        `http://localhost/StockManagementSystem/api/endpoints/supplier.php?id=${id}`,
        {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
        }
      )
        .then((response) => response.json())
        .then((data) => {
          const item = data.data;
          document.getElementById("supplierId").value = item.id;
          document.getElementById("itemNameSupplier").value = item.name;
          document.getElementById("contact_info").value = item.contact_info;
          document.getElementById("postal_address").value = item.postal_address;
  
          document.getElementById("modalTitle").textContent = "Edit Supplier";
          document.getElementById("supplierModal").style.display = "block";
        })
        .catch((error) => console.error("Error fetching Supplier:", error));
    };
  
    document.getElementById("supplierForm").addEventListener("submit", function (event) {
        event.preventDefault();
  
        const formData = new FormData(this);
        const supplierId = document.getElementById("supplierId").value;
  
        const url = supplierId
          ? `http://localhost/StockManagementSystem/api/endpoints/supplier.php?id=${supplierId}`
          : "http://localhost/StockManagementSystem/api/endpoints/supplier.php";
        const method = supplierId ? "PUT" : "POST";
  
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
              document.getElementById("supplierModal").style.display = "none";
              loadSuppliers();
            } else {
              console.error("Error saving supplier item:", data.message);
              document.getElementById("supplierModal").style.display = "none";
              loadSuppliers();
            }
          })
          .catch((error) => console.error("Error:", error));
      });
  
    function loadSuppliers() {
      fetch("http://localhost/StockManagementSystem/api/endpoints/supplier.php", {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          const supplierTableBody = document.getElementById("supplierTableBody");
          supplierTableBody.innerHTML = "";
  
          data.data.forEach((item) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                  <td>${item.name}</td>
                  <td>${item.contact_info}</td>
                  <td>${item.postal_address}</td>
                  <td>${item.created_at}</td>
                  <td>
                      <button class="button" onclick="editSupplier(${item.id})">Edit</button>
                      <button class="button" onclick="deleteSupplier(${item.id})">Delete</button>
                  </td>
              `;
              supplierTableBody.appendChild(row);
          });
        })
        .catch((error) => console.error("Error loading suppliers:", error));
    }
  
    loadSuppliers();
  });
  