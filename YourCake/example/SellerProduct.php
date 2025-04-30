<?php
/*session_start();    
if(empty($_SESSION['userlogin'])){
    header('location:login.php');
}
else {
$user = $_SESSION['name'];
}

include("db_connection.php");

if (isset($_SESSION['success'])) {
    echo "<script>alert('{$_SESSION['success']}');</script>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<script>alert('{$_SESSION['error']}');</script>";
    unset($_SESSION['error']);
}*/
include("../db_connection.php");
$user = "shamel";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/YourCake/pic/logo.png" />

    <!---  CSS -->
    <link rel="stylesheet" href="SellerNavbar.css">
    <link rel="stylesheet" href="SellerProduct.css">

    <!--- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     
    <title>YourCake</title>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <i class='bx bx-menu toggle icon'></i>
            <div class="image-text">
                <span class="image">
                    <img src="/YourCake/pic/logo.png" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name"> YourCake
                    <span class="user">@<?php echo $user; ?></p>
                </div>
            </div>
            
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    
                    <li class="nav-link">
                        <a href="event.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="example.php">
                            <i class='bx bx-customize icon'></i>
                            <span class="text nav-text">Customization</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-list-ul icon' ></i>
                            <span class="text nav-text">Product List</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-calendar icon' ></i>
                            <span class="text nav-text">Schedule</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-bar-chart-square icon' ></i>
                            <span class="text nav-text">Sales</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-cabinet icon' ></i>
                            <span class="text nav-text">Inventory</span>
                        </a>
                    </li>
                    
                </ul>
            </div>

            <div class="bottom-content">
                <li class="nav-link">
                    <a href="/YourCake/functions/logout.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav>


    <section class="Content">

       
        <div class="container">
            <div class="ProductTable">
                <div class="prodHeader">
                    <i class='bx bx-list-ul icon' ></i>
                    <span class="prodSpan">Product List</span>
                    <button class="viewDeleted"><i class='bx bx-trash'></i>&nbsp&nbspRemoved Items</button><br>
                </div>

                <div class="prodControls">
                    <p>Filtered by:</p>
                    <select name="filter" id="filter-dropdown">
                        <option value="">Filter by</option>
                        <option value="Birthday Cakes">Birthday Cakes</option>
                        <option value="Wedding Cakes">Wedding Cakes</option>
                        <option value="Holiday Cakes">Holiday Cakes</option>
                        <option value="Everyday Cakes">Everyday Cakes</option>
                    </select>

                    <div class="prodControls1">
                        <input type="text" id="search-bar" placeholder="Search" />
                    </div>
                </div>

                <!-- Display Product Table-->
                <table class="ProdTable" id="ProdTable">
                    <thead>
                        <tr class="tableHeader">
                            <th class="hidden">ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th class="hidden">Description</th>
                            <th>Category</th>
                            <th>Price (php)</th>
                            <th>Discount (%)</th>
                            <th>Stocks</th>
                            <th id="deleteHeader">Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Fetch data from product table
                        $sql = "SELECT * FROM product WHERE Seller_Username = '$user' and status = 'Available' ORDER BY ProductID DESC";
                        $result = $conn->query($sql);

                        // read data of each row
                        if ($result->num_rows > 0) {
                            while($row=$result->fetch_assoc()){

                                echo "<tr data-Category='{$row['Category']}'>
                                
                                    <td class= 'hidden' >$row[ProductID]</td>
                                    <td><img src='uploads/$row[Image]' alt='Product Image' width='60' height='60'></td>
                                    <td>$row[ProductName]</td>
                                    <td class= 'hidden' >$row[Description]</td>
                                    <td>$row[Category]</td>
                                    <td>$row[Price]</td>
                                    <td>$row[Discount]</td>
                                    <td>$row[Stocks]</td>
                                    <td><button class='delete-button' data-id= '{$row['ProductID']}'><i class='bx bx-trash'></button></td>
                                </tr>
                                ";

                            }
                        } else {
                            // Show 'No products found' message if no products exist
                            echo "<tr><td colspan='9'>No products found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="formcontainer">
                <form id="productForm" method="POST" action="manage_product.php" enctype="multipart/form-data">
                    <h1>Product Details</h1>
                    
                    <!-- Hidden file input -->
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" required>
                    <input type="hidden" name="ProductID" id="ProductID">
                    <input type="hidden" name="seller_username" id="seller_username" value="<?php echo $user; ?>">

                    <!-- Clickable preview image -->
                    <label for="imageInput">
                    <img id="preview" src="default.jpg" alt="Click to upload"><br>
                    
                    <label>Product Name</label>
                    <input type="text" name="prodName" id="prodName" required>

                    <label>Description</label>
                    <input type="text" name="description" id="description" required>
                    
                    <label>Cake Category</label>
                    <select name="category" id="category-dropdown" required>
                            <option value="">Select Category</option>
                            <option value="Birthday Cakes">Birthday Cakes</option>
                            <option value="Wedding Cakes">Wedding Cakes</option>
                            <option value="Holiday Cakes">Holiday Cakes</option>
                            <option value="Everyday Cakes">Everyday Cakes</option>
                    </select>

                    <label>Price</label>
                    <input type="number" name="price" id="price" required min="0">

                    <label>Discount</label>
                    <input type="number" name="discount" id="discount" required min="0" max="100"> 

                    <label>Stocks</label>
                    <input type="number" name="stocks" id="stocks" required min="0" step="1">

                    <button type="submit" name="add" id="add-update">Add</button>
                    <button type="button" id="clear">Clear</button>
                </form>
            </div>
        </div>

    </section>

    <script src="navbar.js"></script>
    <script src="product.js"></script>
</body>
</html>