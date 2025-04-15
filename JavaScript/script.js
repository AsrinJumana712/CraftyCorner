document.addEventListener("DOMContentLoaded", function () {
  // Select All checkbox
  const selectAllCheckbox = document.getElementById("select-all");
  const itemCheckboxes = document.querySelectorAll(".select-item");

  // Update total price based on selected items
  function updateSelectedTotal() {
    let total = 0;
    const selectedProducts = document.getElementById("selected_products");
    selectedProducts.innerHTML = "";
    let selectedCount = 0;

    document.querySelectorAll(".select-item:checked").forEach((checkbox) => {
      const item = checkbox.closest(".cart-item");
      const productName = item.querySelector("h5").innerText;
      const price = parseFloat(
        item.querySelector(".price").innerText.replace("LKR ", "")
      );
      const qty = parseInt(item.querySelector(".quantity-input").value);
      const subtotal = price * qty;
      total += subtotal;
      selectedCount++;

      const productItem = document.createElement("p");
      productItem.innerHTML = `
        <span class="product-name">${productName}</span>
        <span class="price-details" style="margin-left: 10px;"><br> LKR ${price.toFixed(
          2
        )} x ${qty}</span>
        = <strong>LKR ${subtotal.toFixed(2)}</strong>`;
      selectedProducts.appendChild(productItem);
    });

    document.getElementById("selected_total").innerText = total.toFixed(2);
    selectAllCheckbox.checked = selectedCount === itemCheckboxes.length;
  }

  // Quantity change function
  function changeQuantity(productId, change) {
    const quantityInput = document.getElementById("quantity_" + productId);
    const currentQuantity = parseInt(quantityInput.value);
    const maxQuantity = parseInt(quantityInput.getAttribute("data-max"));
    const newQuantity = currentQuantity + change;

    if (
      newQuantity >= 1 &&
      newQuantity <= maxQuantity &&
      newQuantity !== currentQuantity
    ) {
      quantityInput.value = newQuantity;

      fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${productId}&quantity=${newQuantity}`,
      })
        .then((response) => response.text())
        .then(() => updateSelectedTotal())
        .catch((error) => console.error("Error updating cart:", error));
    } else if (newQuantity > maxQuantity) {
      alert(`Only ${maxQuantity} item(s) available in stock.`);
    }
  }

  // Event: Select All checkbox change
  selectAllCheckbox.addEventListener("change", function () {
    itemCheckboxes.forEach((checkbox) => {
      checkbox.checked = this.checked;
    });
    updateSelectedTotal();
  });

  // Event: Each item checkbox change
  itemCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", updateSelectedTotal);
  });

  // Event: Quantity increase/decrease
  document.querySelectorAll(".btn-quantity").forEach((button) => {
    button.addEventListener("click", function () {
      const quantityInput =
        this.closest(".quantity-selector").querySelector(".quantity-input");
      const productId = quantityInput.id.replace("quantity_", "");
      const change = this.classList.contains("increase") ? 1 : -1;
      changeQuantity(productId, change);
    });
  });

  // Event: Read More toggle
  document.querySelectorAll(".read-more-link").forEach(function (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const parent = this.closest(".product-description");
      parent.querySelector(".short").classList.toggle("d-none");
      parent.querySelector(".full").classList.toggle("d-none");
      this.textContent =
        this.textContent === "Read More" ? "Show Less" : "Read More";
    });
  });

  // Initialize total on page load
  updateSelectedTotal();
});

// Confirm order
function confirmOrder() {
  if (confirm("Are you sure you want to place this order?")) {
    document.getElementById("orderForm").submit();
  }
}

// Hide the reply textarea after admin message submission
function hideTextarea(feedbackId) {
  const replyContainer = document.getElementById(
    "reply-container-" + feedbackId
  );
  if (replyContainer) replyContainer.style.display = "none";
}

const firebaseConfig = { /* your config */ };
firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();
const provider = new firebase.auth.GoogleAuthProvider();

// Then use signInWithPopup like:
auth.signInWithPopup(provider)
  .then((result) => {
    const user = result.user;
    console.log(user);  // Check the user details in the console
    // Further actions after successful login can go here
  })
  .catch((error) => {
    console.error("Error during Google Sign-In:", error);
  });
