document.getElementById('imageInput').addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});

document.getElementById("discount").addEventListener("input", function () {
  if (this.value > 100) this.value = 100;
});

document.getElementById('stocks').addEventListener('input', function (e) {
  // Remove decimal points
  if (e.target.value.includes('.')) {
      e.target.value = e.target.value.split('.')[0];
  }
});

function selectRow(row) {
  // Access data-* attributes
  const ProductID = row.dataset.productid;
  const ProductName = row.dataset.productname;
  const Category = row.dataset.category;
  const Description = row.dataset.description;
  const Price = row.dataset.price;
  const Discount = row.dataset.discount;
  const Stocks = row.dataset.stocks;
  const Image = row.dataset.image;

  // Example: populate form fields
  document.getElementById('ProductID').value = ProductID;
  document.getElementById('prodName').value = ProductName;
  document.getElementById('description').value = Description;
  document.getElementById('category-dropdown').value = Category;
  document.getElementById('price').value = Price;
  document.getElementById('discount').value = Discount;
  document.getElementById('stocks').value = Stocks;
  document.getElementById('preview').src = Image;

  //disable input fields
  document.getElementById('prodName').disabled = true;
  document.getElementById('imageInput').required = false;

  //for verification
  console.log("Selected Product:", ProductID);

  //Change form action to update
  const addBtn = document.getElementById("add");
  addBtn.name = "update";
  addBtn.textContent = 'Update';

}

document.getElementById("clear").addEventListener("click", function () {
  // Clear the form fields
  document.getElementById("productForm").reset();

  // Clear the hidden ProductID (for update)
  document.getElementById("ProductID").value = "";

  // Reset the image preview
  document.getElementById("preview").src = "default.jpg";

  // Switch Update -> Add
  const addBtn = document.getElementById("add");
  addBtn.textContent = "Add";
  addBtn.name = "add";

  document.getElementById('prodName').disabled = false;
  document.getElementById('imageInput').required = true;
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".delete-button").forEach(button => {
      button.addEventListener("click", function (e) {
          e.stopPropagation(); // Prevent triggering row click
          const productId = this.dataset.id;

          if (confirm("Are you sure you want to delete this product?")) {
              fetch("/YourCake/functions/manage_product.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `delete=1&ProductID=${productId}`
              })
              .then(response => response.text())
              .then(data => {
                  alert(data);
                  location.reload();
              })
              .catch(err => {
                  alert("Failed to delete the product.");
                  console.error(err);
              });
          }
      });
  });
});

// Get references to the search input, button, and table
const searchInput = document.getElementById("search-bar");
const table = document.getElementById("ProdTable");
const tableRows = table.querySelectorAll("tbody tr");

// Function to filter the table based on the search input
function searchTable() {
  const query = searchInput.value.toLowerCase(); // Get the search input and convert to lowercase for case-insensitive comparison

  tableRows.forEach(row => {
    const rowText = row.textContent.toLowerCase(); // Get the text content of each row and convert to lowercase

    if (rowText.includes(query)) {
      row.style.display = ""; // If the row matches the query, show it
    } else {
      row.style.display = "none"; // If the row doesn't match, hide it
    }
  });
}

// Automatically search as the user types
searchInput.addEventListener("input", searchTable);

document.addEventListener('DOMContentLoaded', function () {
  // Get references to the dropdown and table rows
  const filterDropdown = document.getElementById("filter-dropdown");
  const tableRows = document.querySelectorAll("#ProdTable tbody tr");

  // Function to filter the table based on selected Category
  function filterTable() {
    const selectedCategory = filterDropdown.value; // Get selected Category from the dropdown

    tableRows.forEach(row => {
      const rowCategory = row.getAttribute("data-Category"); // Get the Category from the data attribute

      // Check if the selected Category matches the row's Category
      if (selectedCategory === "" || rowCategory === selectedCategory) {
        row.style.display = ""; // Show the row if it matches the selected Category
      } else {
        row.style.display = "none"; // Hide the row if it doesn't match
      }
    });
  }

  // Event listener to filter the table when the dropdown value changes
  filterDropdown.addEventListener("change", filterTable);

  // Initialize filtering on page load
  filterTable();
});