document.addEventListener("DOMContentLoaded", function () {
    const salesModal = document.getElementById("salesModal");
    const addsaleButton = document.getElementById("addSaleButton");
    const closeModalButton = document.getElementById("salemodalClose");
    const salesForm = document.getElementById("salesForm");
    const itemNameSale = document.getElementById("itemNameSale");
    const quantitySale = document.getElementById('quantitySale');
    let unitPriceSale = document.getElementById('unitPriceSale');
    let salesModalCustomerId = document.getElementById("salesModalCustomerId");

    quantitySale.addEventListener('keyup', (e) => {
      document.getElementById('totalAmountSale').value = unitPriceSale.value * e.target.value;
    });
    unitPriceSale.addEventListener('keyup', (e) => {
      document.getElementById('totalAmountSale').value = quantitySale.value * e.target.value;
    });
    const loggedInUserId =localStorage.getItem('loggedInUserId');
    document.getElementById('userIdSale').value = loggedInUserId;


    axios.get('http://localhost/StockManagementSystem/api/endpoints/sale.php?fetch_items=true')
        .then(data => {
            if (data.data.success) {
                const itemDropdown = document.getElementById('itemNameSale');
                itemDropdown.innerHTML = '';

                data.data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    itemDropdown.appendChild(option);
                });
            } else {
                console.error("Error fetching items:", data.data.message);
            }
        })
        .catch(error => console.error("Error:", error));

  
    addsaleButton.addEventListener("click", function () {
      openModal("Add New sale");
    });
  
    closeModalButton.addEventListener("click", closeModal);
  
    function openModal(title) {
      document.getElementById("modalTitleSale").textContent = title;
      salesModal.style.display = "block";
    }
  
    function closeModal() {
      salesModal.style.display = "none";
      salesForm.reset();
    }
    window.deleteSale = function (id) {
      if (confirm("Are you sure you want to delete this sale item?")) {
        axios.delete(`http://localhost/StockManagementSystem/api/endpoints/sale.php?id=${id}`)
          .then((data) => {
            if (data.data.success) {
              loadSalesData();
            } else {
              console.error("Error deleting sale item:", data.data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    };

    function checkChange(){
      const custSelCheck = document.getElementById("custSelCheck");

      if(custSelCheck.checked==true){
        salesModalCustomerId.style.display='none';
        salesModalCustomerId.value= null;
      }else{
        salesModalCustomerId.style.display='block';
      }
    }

    custSelCheck.addEventListener("change",()=>{
      checkChange();
    });

    //AXIOS----------
    //status field
    

    // Fetch item details
    itemNameSale.addEventListener("change", function () {
      const itemId = itemNameSale.value;

      if (itemId) {
          axios.get(`http://localhost/StockManagementSystem/api/endpoints/sale.php?item_id=${itemId}`)
          .then(data => {
              if (data.data.success) {
                  const item = data.data.data;
                  document.getElementById('unitPriceSale').value = item.selling_price;
              } else {
                  console.error("Error fetching item details:", data.data.message);
              }
          })
          .catch(error => console.error("Error:", error));
      
      }
  });

    axios.get('http://localhost/StockManagementSystem/api/endpoints/sale.php?customer=true')
    .then(data => {
        if (data.data.success) {
            salesModalCustomerId.innerHTML = '';

            data.data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                salesModalCustomerId.appendChild(option);
            });
        } else {
            console.error("Error fetching items:", data.data.message);
        }
    })
    .catch(error => console.error("Error:", error));
  
    document.getElementById("salesForm").addEventListener("submit", function (event) {
      event.preventDefault();
    
      const formData = new FormData(this);
      const saleId = document.getElementById("salesId").value;
    
      const url = saleId
        ? `http://localhost/StockManagementSystem/api/endpoints/sale.php?id=${saleId}`
        : "http://localhost/StockManagementSystem/api/endpoints/sale.php";
    
      const data = Object.fromEntries(formData);
      
      const request = saleId 
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
            document.getElementById("salesModal").style.display = "none";
            loadSalesData();
          } else {
            if (response.data.error) {
              alert(response.data.error);
            }
            document.getElementById("salesModal").style.display = "none";
            loadSalesData();
          }
        })
        .catch((error) => console.error("Error:", error));
    });
    
  
    function loadSalesData() {
      axios.get(`http://localhost/StockManagementSystem/api/endpoints/sale.php?user_id=${loggedInUserId}`)
        .then((data) => {
          const salesTableBody = document.getElementById("salesTableBody");
          salesTableBody.innerHTML = "";
  
          data.data.data.forEach((sale) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                  <td>${sale.item_name}</td>
                  <td>${sale.quantity}</td>
                  <td>${sale.unit_price}</td>
                  <td>${sale.total_amount}</td>
                  <td>${sale.customer_name==null?'Not Recorded':sale.customer_name}</td>
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
  
