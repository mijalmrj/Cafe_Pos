<!DOCTYPE html>
<html lang="en">

<head>
    <title>Confirmed Order</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .receipt-body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .receipt-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .order-confirmed {
            color: #4CAF50;
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .receipt-details {
            margin: 20px 0;
        }

        .addedOrderInfo {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .addedOrderInfo img {
            margin-right: 10px;
        }

        .receipt-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
        }

        .receipt-button:hover {
            background-color: #45a049;
        }

        .thank-you-message {
            text-align: center;
            margin-top: 20px;
            font-size: 1.5em;
            color: #333;
            display: none;
            /* Initially hidden */
        }
    </style>
</head>

<body class="receipt-body">
    <div class="container">
        <!-- Navigation bar -->
        <header class="navbar">
            <div class="navhome">
                <a href="index.html"><img src="logos/coffee_logo.png" id="coffee-logo"></a>
                <a href="index.html">
                    <h2>Northside Café</h2>
                </a>
            </div>
            <div class="generallinks">
                <a href="transactions_admin.html"><img src="logos/print.png"></a>
                <a href="user.html"><img src="logos/user.png"></a>
            </div>
        </header>

        <!-- Payment Options -->
        <div class="payment-option">
            <h3>Select Payment Method</h3>
            <button class="payment-button" id="payment-cash">Cash</button>
            <button class="payment-button" id="payment-card">Card</button>
        </div>

        <div class="main-receipts">
            <div class="receipt-box">
                <h1 class="order-confirmed">Order Confirmed</h1>
                <div class="receipt-details">
                    <h2 id="receipt-orderno"></h2>

                    <div id="receipt-orderdetails"></div>
                    <!-- Receipt details -->
                    <h2 id="receipt-orderamount"></h2>
                    <!-- Total amount -->
                </div>
            </div>

            <!-- Thank you message -->
            <div class="thank-you-message" id="thank-you-message">
                <h2>Thank you for your order!</h2>
            </div>
        </div>
    </div>

    <script>
        let itemList = [];
        window.onload = function() {
            itemList = JSON.parse(localStorage.getItem("cart"));

            calculateTotal();
            generateReceiptData();

            handleReceiptOption(true);
        }

        function createReceiptItem(itemDetails) {
            let itemContainer = document.createElement("div");
            itemContainer.className = "addedOrderInfo";
            itemContainer.innerHTML = `
                <p>${itemDetails.no}</p>
                <img src="${itemDetails.imgSrc}" style="width: 100px; height: 100px;">
                <div>
                    <p>${itemDetails.name}</p>
                    <p>Size: ${itemDetails.size}</p>
                    <p>${itemDetails.milk ? "Milk: " + itemDetails.milk : ""}</p>
                    <p>${itemDetails.takeawayText}</p>
                    <p>${itemDetails.sugarText ? itemDetails.sugarText : ""}</p>
                    <p>Cost: $${itemDetails.cost}</p>
                </div>
            `;
            return itemContainer;
        }

        function generateReceiptData() {
            let receiptElement = document.getElementById("receipt-orderdetails");
            receiptElement.innerHTML = "";
            if (!itemList || itemList.length === 0) {
                receiptElement.innerHTML = "<p>No items in the order.</p>";
                return;
            }
            itemList.forEach(itemDetails => {
                let node = createReceiptItem(itemDetails);
                receiptElement.appendChild(node);
            });
        }

        function calculateTotal() {
            let totElement = document.getElementById("receipt-orderamount");
            totElement.innerHTML = "Total: $0";
            if (!itemList || itemList.length === 0) {
                return;
            }
            let total = itemList.map(item => item.cost).reduce((a, b) => Number(a) + Number(b), 0);
            console.log(total)
            totElement.innerHTML = `Total: $${total}`;
        }

        function sendOrderDataToBackend(orderData) {
            console.log(orderData)
            fetch("confirmation.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => response.text())
                .then(data => {
                    // window.location.href = `index.php`;
                    console.log(data)
                })
                .catch(error => {
                    console.error("Error saving order:", error);
                });
        }

        function handleReceiptOption(print) {
            let orderData = {
                order: {
                    totalAmount: document.getElementById("receipt-orderamount").innerText.replace("Total: $", ""),
                    shippingMethod: "Dine in",
                    shippingTime: new Date().toISOString(),
                    shippingLocation: "NorthSide Cafe",
                    orderStatus: "Confirmed"
                },
                orderDetails: JSON.parse(localStorage.getItem("cart"))
            };

            sendOrderDataToBackend(orderData);

            if (print) {
                downloadReceipt();
            } else {
                document.getElementById("thank-you-message").style.display = "block";
                document.querySelector(".payment-option").style.display = "none"; // Hide payment options
            }
        }

        // Function to download the receipt as a file
        function downloadReceipt() {
            let receiptContent = document.getElementById("receipt-orderdetails").innerHTML;
            let totalAmount = document.getElementById("receipt-orderamount").innerHTML;
            let receiptData = `<h1>Receipt</h1>${receiptContent}<p>${totalAmount}</p>`;

            let blob = new Blob([receiptData], {
                type: "text/html"
            });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "receipt.html";
            link.click();
        }

        // Handle payment option selection
        function handlePaymentOption(option) {
            if (option === 'cash') {
                window.location.href = 'cash_calculator.html'; // Redirect to cash calculator
            } else if (option === 'card') {
                window.location.href = 'eftpos_payment.html'; // Redirect to EFTPOS payment page
            }
        }

        // Event listener for CASH button
        document.getElementById("payment-cash").addEventListener("click", function() {
            handlePaymentOption('cash');
        });

        // Event listener for CARD button
        document.getElementById("payment-card").addEventListener("click", function() {
            handlePaymentOption('card');
        });
    </script>
</body>

</html>

</html>

