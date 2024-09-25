/*
#Updated: Added cash calculator functionality
*/

const availableMethods = {
    "cash": document.querySelector(".payment-options .cash-option"),
    "card": document.querySelector(".payment-options .card-option")
};

const cashCalculator = document.getElementById("cash-calculator");
const cashAmountInput = document.getElementById("cash-amount");
const changeDisplay = document.getElementById("change-display");

function enablePaymentUpdate() {
    for (let option in availableMethods) {
        availableMethods[option].onclick = (event) => {
            localStorage.setItem("paymentMethod", option);
            updateColor();

            function enablePaymentUpdate() {
    for (let option in availableMethods) {
        availableMethods[option].onclick = (event) => {
            localStorage.setItem("paymentMethod", option);
            updateColor();

            // Redirect to the cash calculator page if "Cash" is selected
            if (option === "cash") {
                // Store the total order amount in local storage (you can change this value as per your order total)
                localStorage.setItem("totalOrderAmount", 20); // Example order amount
                window.location.href = "cash_calculator.html"; // Redirect to cash calculator
            }
        }
    }}

}
}


    // Event listener for cash amount calculation
    document.getElementById("calculate-change").onclick = function () {
        const totalOrderAmount = 20; // Replace this with your actual order total
        const cashReceived = parseFloat(cashAmountInput.value);
        if (!isNaN(cashReceived) && cashReceived >= totalOrderAmount) {
            const change = cashReceived - totalOrderAmount;
            changeDisplay.textContent = `Change to return: $${change.toFixed(2)}`;
        } else {
            changeDisplay.textContent = "Please enter a valid amount.";
        }
    };
}

function updateColor() {
    let currentOption = localStorage.getItem("paymentMethod");
    for (let option in availableMethods) {
        if (option == currentOption) {
            availableMethods[option].classList.add("selectedPaymentBtn");
            availableMethods[option].classList.remove("defaultPaymentBtn");
        } else {
            availableMethods[option].classList.add("defaultPaymentBtn");
            availableMethods[option].classList.remove("selectedPaymentBtn");
        }
    }
}

// Call enablePaymentUpdate on page load
document.addEventListener("DOMContentLoaded", enablePaymentUpdate);

document.addEventListener("DOMContentLoaded", () => {
    const cashAmountInput = document.getElementById("cash-amount");
    const changeDisplay = document.getElementById("change-display");

    // If the cash calculator is on this page
    if (cashAmountInput) {
        const totalOrderAmount = localStorage.getItem("totalOrderAmount"); // Get total from local storage
        document.getElementById("calculate-change").onclick = function () {
            const cashReceived = parseFloat(cashAmountInput.value);
            if (!isNaN(cashReceived) && cashReceived >= totalOrderAmount) {
                const change = cashReceived - totalOrderAmount;
                changeDisplay.textContent = `Change to return: $${change.toFixed(2)}`;
            } else {
                changeDisplay.textContent = "Please enter a valid amount.";
            }
        };
    }
});
