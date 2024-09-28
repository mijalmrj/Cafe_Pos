function downloadReceipt(orderId) {
    // Fetch order details from the server
    fetch(`get_order_details.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            // Log the response to the console for debugging
            console.log("Received data:", data);

            // Check if the response is an array
            if (Array.isArray(data)) {
                let receiptContent = `<h1>Receipt</h1>`;
                let totalAmount = 0;

                data.forEach(item => {
                    receiptContent += `
                        <p>${item.product_name} (Size: ${item.size}, Price: $${item.price})</p>
                    `;
                });

                if (data.length > 0) {
                    totalAmount = parseFloat(data[0].total_amount);
                }

                receiptContent += `<p><strong>Total Amount: $${totalAmount.toFixed(2)}</strong></p>`;

                // Create a blob and download the receipt
                let blob = new Blob([receiptContent], {
                    type: "text/html"
                });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = `receipt_${orderId}.html`; // Naming the receipt with the order ID
                link.click();
            } else {
                console.error("Expected an array, but got:", data);
            }
        })
        .catch(error => {
            console.error("Error fetching order details:", error);
        });
}