//image preview
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

//input rules
  document.getElementById("discount").addEventListener("input", function () {
    if (this.value > 100) this.value = 100;
  });

  document.getElementById('stocks').addEventListener('input', function (e) {
    // Remove decimal points
    if (e.target.value.includes('.')) {
        e.target.value = e.target.value.split('.')[0];
    }
  });

//clear form
  document.getElementById("clear").addEventListener("click", function () {
    // Clear the form fields
    document.getElementById("productForm").reset();

    // Clear the hidden ProductID (for update)
    document.getElementById("ProductID").value = "";

    // Reset the image preview
    document.getElementById("preview").src = "default.jpg";

    // Switch Update -> Add
    const addBtn = document.getElementById("add-update");
    addBtn.textContent = "Add";
    addBtn.name = "add";

    document.getElementById('prodName').readOnly = false;
    document.getElementById('imageInput').required = true;
  });

  //ADD
  $(document).ready(function(){
    // Handle form submission
    $("#productForm").submit(function(e){
        e.preventDefault(); // Prevent the form from submitting normally




        var formData = new FormData(this); // Collect form data including files

        $.ajax({
            url: 'manage_product.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.trim() === "success"){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product saved successfully!'
                    });
                    $("#productForm")[0].reset();
                    $("#preview").attr("src", "default.jpg"); // reset image preview
                    fetchProducts(); // reload table
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!'
                    });
                }
            }
        });
    });

    // Fetch products and refresh table
    function fetchProducts(){
        $.ajax({
            url: "fetch_products.php",
            type: "GET",
            success: function(data){
                $("#ProdTable tbody").html(data); 
                updateTable();
            }
        });
    }

    $("#add-update").text("Add");

});

//delete
$(document).on('click', '.delete-button', function(){
  var id = $(this).data('id');

  Swal.fire({
      title: 'Are you sure?',
      text: "Product will be deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: 'delete_product.php', 
              type: 'POST',
              data: { ProductID: id },
              success: function(response){
                  if(response.trim() === "success"){
                      Swal.fire(
                          'Deleted!',
                          'Product has been deleted.',
                          'success'
                      );
                      $("#add-update").text("Add");
                      $("#productForm")[0].reset();
                      $("#preview").attr("src", "default.jpg");
                      fetchProducts(); 
                      
                  } else {
                      Swal.fire(
                          'Error!',
                          'Product not deleted!',
                          'error'
                      );
                  }
              }
          });
      }
      else{
        $("#add-update").text("Add");
        $("#productForm")[0].reset();
        $("#preview").attr("src", "default.jpg");
      }
  });

  // Fetch products and refresh table
  function fetchProducts(){
    $.ajax({
        url: "fetch_products.php", // we'll create this next
        type: "GET",
        success: function(data){
            $("#ProdTable tbody").html(data); 
            updateTable();
        }
    });
  }
});

//select row
$(document).ready(function(){
  $(document).on('click', '#ProdTable tbody tr', function(e){
      if($(e.target).hasClass('delete-button')) {
          // If clicked on delete button, do nothing
          return;
      }

      var $row = $(this).children("td");

      var productID = $row.eq(0).text().trim();
      var imageSrc = $row.eq(1).find('img').attr('src');
      var prodName = $row.eq(2).text().trim();
      var description = $row.eq(3).text().trim();
      var category = $row.eq(4).text().trim();
      var price = $row.eq(5).text().trim();
      var discount = $row.eq(6).text().trim();
      var stocks = $row.eq(7).text().trim();

      // Populate the form fields
      $("#ProductID").val(productID);
      $("#prodName").val(prodName);
      $("#description").val(description);
      $("#category-dropdown").val(category);
      $("#price").val(price);
      $("#discount").val(discount);
      $("#stocks").val(stocks);

      // Set the preview image
      $("#preview").attr("src", imageSrc);

      // Change button text from "Add" ➔ "Update"
      $("#add-update").text("Update");

      // Disable certain fields for update
      document.getElementById('prodName').readOnly = true;
      document.getElementById('imageInput').required = false;
  });
});


const searchInput = document.getElementById("search-bar");
const filterDropdown = document.getElementById("filter-dropdown");
const table = document.getElementById("ProdTable");

// Combined filter function
function updateTable() {
  const tableRows = table.querySelectorAll("tbody tr");
  const query = searchInput.value.toLowerCase();
  const selectedCategory = filterDropdown.value;

  tableRows.forEach(row => {
    const rowText = row.textContent.toLowerCase();
    const rowCategory = row.getAttribute("data-Category");

    // Check both search text and category
    const matchesSearch = rowText.includes(query);
    const matchesCategory = selectedCategory === "" || rowCategory === selectedCategory;

    if (matchesSearch && matchesCategory) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

// Attach the combined function to both search and dropdown
searchInput.addEventListener("input", updateTable);
filterDropdown.addEventListener("change", updateTable);

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateTable);

