document.addEventListener("DOMContentLoaded", function () {

    console.log('transactions file loaded')

    const loggedInUserId =localStorage.getItem('loggedInUserId');

    function loadTransactionsData() {
      fetch(`http://localhost/StockManagementSystem/api/endpoints/transaction.php?user_id=${loggedInUserId}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          const transactionTableBody = document.getElementById("transactionTableBody");
          transactionTableBody.innerHTML = "";
  
          data.data.forEach((sale) => {
            const row = document.createElement("tr");
            row.innerHTML = `
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
  
