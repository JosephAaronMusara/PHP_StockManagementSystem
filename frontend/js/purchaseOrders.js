document.addEventListener("DOMContentLoaded", function () {
    const porderModal = document.getElementById("porderModal");
    const submitBtnPO = document.getElementById("submitBtnPO");
    const closeModalButton = document.getElementById("pomodalClose");
    const addPOrderButton = document.getElementById("addPOrderButton");
    const porderForm = document.getElementById("porderForm");
    const itemNamePO = document.getElementById("itemNamePO");
    const quantityPO = document.getElementById('quantityPO');
    let unitPricePO = document.getElementById('unitPricePO');

    quantityPO.addEventListener('keyup', (e) => {
      document.getElementById('totalAmountPO').value = unitPricePO.value * e.target.value;
    });  

    const loggedInUserId =localStorage.getItem('loggedInUserId');
    document.getElementById('userIdPO').value = loggedInUserId;

    addPOrderButton.addEventListener("click", function () {
        openModal("Add New Purchase Order");
      });

    fetch('http://localhost/StockManagementSystem/api/endpoints/stock.php?fetch_items=true')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const itemDropdown = document.getElementById('supplierPO');
            itemDropdown.innerHTML = '';
            const supOption = document.createElement('option');
            supOption.value=null;
            supOption.textContent = 'Select Supplier :';
            itemDropdown.appendChild(supOption);  
 
            data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                itemDropdown.appendChild(option);
            });
        } else {
            console.error("Error fetching items:", data.message);
        }
    })
    .catch(error => console.error("Error:", error));

    fetch('http://localhost/StockManagementSystem/api/endpoints/sale.php?fetch_items=true')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemnameDropdown = document.getElementById('itemNamePO');
                itemnameDropdown.innerHTML = '';
                const itemOption = document.createElement('option');
                itemOption.value=null;
                itemOption.textContent = 'Select Item Name :';
                itemnameDropdown.appendChild(itemOption); 

                data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    itemnameDropdown.appendChild(option);
                });
            } else {
                console.error("Error fetching items:", data.message);
            }
        })
        .catch(error => console.error("Error:", error));

            // Fetch item details
        itemNamePO.addEventListener("change", function () {
        const itemId = itemNamePO.value;
  
        if (itemId) {
            fetch(`http://localhost/StockManagementSystem/api/endpoints/sale.php?item_id=${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.data)
                    const item = data.data;
                    document.getElementById('unitPricePO').value = item.purchase_price;
                } else {
                    console.error("Error fetching item details:", data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        
        }
    });
  
    closeModalButton.addEventListener("click", closeModal);
  
    function openModal(title) {
      document.getElementById("modalTitle").textContent = title;
      porderModal.style.display = "block";
    }
  
    function closeModal() {
      porderModal.style.display = "none";
      porderForm.reset();
    }
    window.deletePO = function (id) {
      if (confirm("Are you sure you want to delete this Purchase Order?")) {
        fetch(
          `http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}&id=${id}`,
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
              loadPurchaseOrders();
            } else {
              console.error("Error deleting Purchase Order :", data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    };

    window.acknowledgePO = function (id) {
      fetch(
        `http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}&id=${id}`,
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
          const received_data = {
            "name": item.item_name,
            "category_id": 1,
            "supplier_id" :item.supplier_id,
            "purchase_price" : item.unit_price,
            "selling_price":item.unit_price,
            "quantity" : item.quantity,
          };
          console.log(received_data);
          fetch("http://localhost/StockManagementSystem/api/endpoints/stock.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify(received_data),
          })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert("Successful");
            } else {
              alert("Error saving stock item:", data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
//
        })
        .catch((error) => console.error("Error recording Order :", error));
    };


    window.editPO = function (id) {
        fetch(
          `http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}&id=${id}`,
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
            document.getElementById("porderId").value = item.purchase_order_id;
            document.getElementById("userIdPO").value = item.user_id;
            document.getElementById("supplierPO").value = item.supplier_name
            document.getElementById("itemNamePO").value = item.item_name;
            document.getElementById("quantityPO").value = item.quantity;
            document.getElementById("unitPricePO").value = item.unit_price;
            document.getElementById("totalAmountPO").value = item.total_amount;
            document.getElementById("time_received").value = item.received_at;
    
            document.getElementById("modalTitlePO").textContent = "Edit Purchase Order";
            document.getElementById("porderModal").style.display = "block";
          })
          .catch((error) => console.error("Error fetching PO:", error));
      };
  
    document.getElementById("porderForm").addEventListener("submit", function (event) {
        event.preventDefault();
  
        const formData = new FormData(this);
        const porderId = document.getElementById("porderId").value;
  
        const url = porderId
          ? `http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}&id=${porderId}`
          : `http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}`;
        const method = porderId ? "PUT" : "POST";
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
              document.getElementById("porderModal").style.display = "none";
              loadPurchaseOrders();
            } else {
              console.error("Error saving purchase Order data:", data.message);
              document.getElementById("porderModal").style.display = "none";
              loadPurchaseOrders();
            }
          })
          .catch((error) => console.error("Error:", error));
      });
  
    function loadPurchaseOrders() {
      fetch(`http://localhost/StockManagementSystem/api/endpoints/purchaseOrder.php?user_id=${loggedInUserId}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          const porderTableBody = document.getElementById("porderTableBody");
          porderTableBody.innerHTML = "";
  
          data.data.forEach((purchaseOrderData) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                  <td>${purchaseOrderData.supplier_name}</td>
                  <td>${purchaseOrderData.item_name}</td>
                  <td>${purchaseOrderData.quantity}</td>
                  <td>${purchaseOrderData.unit_price}</td>
                  <td>${purchaseOrderData.total_amount}</td>
                  <td>${purchaseOrderData.received_at}</td>
                  <td>
                    <button class="button" onclick="editPO(${purchaseOrderData.purchase_order_id})">Edit</button>
                    <button class="button" onclick="deletePO(${purchaseOrderData.purchase_order_id})">Delete</button>
                    <button class="button" onclick="acknowledgePO(${purchaseOrderData.purchase_order_id})">Received</button>
                  </td>
              `;
              porderTableBody.appendChild(row);
          });
        })
        .catch((error) => console.error("Error loading PO data:", error));
    }
  
    loadPurchaseOrders();
  });