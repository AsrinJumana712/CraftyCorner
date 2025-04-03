// Ensure checkboxes are checked based on the previous state (if selected previously)
document.getElementById("select-all").addEventListener("change", function () {
  let checkboxes = document.querySelectorAll(".select-item");
  checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  updateSelectedTotal();
});

function updateSelectedTotal() {
  let total = 0;
  let selectedProducts = document.getElementById("selected_products");
  selectedProducts.innerHTML = ""; // Clear existing list

  document.querySelectorAll(".select-item:checked").forEach((checkbox) => {
    let item = checkbox.closest(".cart-item");
    let productName = item.querySelector("h5").innerText;
    let price = parseFloat(
      item.querySelector(".price").innerText.replace("LKR ", "")
    );
    let qty = parseInt(item.querySelector(".quantity-input").value);
    let subtotal = price * qty;
    total += subtotal;

    // Create a paragraph element for each selected product with space between product name and price
    let productItem = document.createElement("p");
    productItem.innerHTML = `<span class="product-name">${productName}</span> <span class="price-details" style="margin-left: 10px;"> <br> LKR ${price.toFixed(
      2
    )} x ${qty}</span> = LKR ${subtotal.toFixed(2)}`;
    selectedProducts.appendChild(productItem);
  });

  document.getElementById("selected_total").innerText = total.toFixed(2);
}

// Event listener for quantity changes and item selection
document
  .querySelectorAll(".select-item, .quantity-selector button")
  .forEach((el) => {
    el.addEventListener("input", updateSelectedTotal);
  });

// Initialize total and selected items on page load
updateSelectedTotal();

function changeQuantity(productId, change) {
  let quantityInput = document.getElementById("quantity_" + productId);
  let currentQuantity = parseInt(quantityInput.value);
  if (currentQuantity + change >= 1) {
    quantityInput.value = currentQuantity + change;
    updateSelectedTotal();
  }
}
