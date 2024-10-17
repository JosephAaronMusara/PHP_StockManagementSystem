document.addEventListener("DOMContentLoaded", function () {

    function loadTransactionsData() {
      axios.get(`http://localhost/StockManagementSystem/api/endpoints/transaction.php`)
        .then((response) => {
          const transactionTableBody = document.getElementById("transactionTableBody");
          transactionTableBody.innerHTML = "";
  
          response.data.data.forEach((sale) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                  <td>${sale.cashier}</td>
                  <td>${sale.transaction_type}</td>
                  <td>${sale.item_name}</td>
                  <td>${sale.quantity}</td>
                  <td>${sale.transaction_date}</td>
              `;
              transactionTableBody.appendChild(row);
          });
        })
        .catch((error) => console.error("Error loading transaction data:", error));
    }
  
    loadTransactionsData();
  });
  
