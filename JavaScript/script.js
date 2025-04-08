document.addEventListener("DOMContentLoaded", function () {
  // Select all checkbox functionality
  let selectAllCheckbox = document.getElementById("select-all");
  let itemCheckboxes = document.querySelectorAll(".select-item");

  selectAllCheckbox.addEventListener("change", function () {
    itemCheckboxes.forEach((checkbox) => {
      checkbox.checked = this.checked;
    });
    updateSelectedTotal();
  });

  // Update total price based on selected items
  function updateSelectedTotal() {
    let total = 0;
    let selectedProducts = document.getElementById("selected_products");
    selectedProducts.innerHTML = ""; // Clear existing list
    let selectedCount = 0;

    document.querySelectorAll(".select-item:checked").forEach((checkbox) => {
      let item = checkbox.closest(".cart-item");
      let productName = item.querySelector("h5").innerText;
      let price = parseFloat(
        item.querySelector(".price").innerText.replace("LKR ", "")
      );
      let qty = parseInt(item.querySelector(".quantity-input").value);
      let subtotal = price * qty;
      total += subtotal;
      selectedCount++;

      // Create an item entry in the checkout summary
      let productItem = document.createElement("p");
      productItem.innerHTML = `<span class="product-name">${productName}</span>
        <span class="price-details" style="margin-left: 10px;"><br> LKR ${price.toFixed(
          2
        )} x ${qty}</span>
        = <strong>LKR ${subtotal.toFixed(2)}</strong>`;
      selectedProducts.appendChild(productItem);
    });

    document.getElementById("selected_total").innerText = total.toFixed(2);

    // Toggle "Select All" based on individual selections
    selectAllCheckbox.checked = selectedCount === itemCheckboxes.length;
  }

  // Event listeners for checkboxes
  itemCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", updateSelectedTotal);
  });

  // Quantity adjustment
  document.querySelectorAll(".btn-quantity").forEach((button) => {
    button.addEventListener("click", function () {
      let quantityInput =
        this.closest(".quantity-selector").querySelector(".quantity-input");
      let productId = quantityInput.id.replace("quantity_", "");
      let change = this.classList.contains("increase") ? 1 : -1;
      changeQuantity(productId, change);
    });
  });

  function changeQuantity(productId, change) {
    let quantityInput = document.getElementById("quantity_" + productId);
    let currentQuantity = parseInt(quantityInput.value);
    let maxQuantity = parseInt(quantityInput.getAttribute("data-max")); // Get max from data attribute
    let newQuantity = currentQuantity + change;
  
    if (newQuantity >= 1 && newQuantity <= maxQuantity && newQuantity !== currentQuantity) {
      quantityInput.value = newQuantity;
  
      // Send AJAX request to update session
      fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${productId}&quantity=${newQuantity}`
      })
        .then((response) => response.text())
        .then(() => updateSelectedTotal())
        .catch((error) => console.error("Error updating cart:", error));
    } else if (newQuantity > maxQuantity) {
      alert(`Only ${maxQuantity} item(s) available in stock.`);
    }
  }
  
  // Initialize total and selected items on page load
  updateSelectedTotal();
});

//confirm order (checkout confirm)
function confirmOrder() {
  if (confirm("Are you sure you want to place this order?")) {
    document.getElementById("orderForm").submit();
  }
}

 // Hide the reply textarea after submission (admin message)
function hideTextarea(feedbackId) {
  var replyContainer = document.getElementById('reply-container-' + feedbackId);
  replyContainer.style.display = 'none';
}