<?php

include __DIR__ . '/../db_connection.php';

if (isset($_POST['add'])) {
    $user = $_POST['seller_username'];
    $prodName = $_POST['prodName'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $stocks = $_POST['stocks'];
    $status = "Available";
    //$image_name = $_FILES['image']['name'];

    //$tmp = explode(".",$image_name);
    //$newfilename = round(microtime(true)).'.'.end($tmp);
    //$uploadpath = "uploads/" . $newfilename;
    //move_uploaded_file($_FILES['image']["tmp_name"], $uploadpath);

    // Handle image upload
    if (isset($_FILES['image'])) {
        $image = $_FILES['image']['name']; // Get the file name
        $target = "uploads/" . basename($image); // Set target upload path

        // Create the uploads directory if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads'); // Create uploads directory
        }

        // Move the uploaded file to the target location
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $response['image'] = $image; // Success message
        } else {
            $response['error'] = "File upload failed.";
            echo json_encode($response);
            exit; // Exit if upload fails
        }
    }

    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM product");
    $row = mysqli_fetch_assoc($result);
    $newID = "PROD" . ($row['total'] + 1);

    $sql = "INSERT INTO product(ProductID, Seller_Username, ProductName, Category, Description, Discount, Stocks, Price, Image, status) 
            VALUES ('$newID', '$user', '$prodName', '$category', '$description', $discount, $stocks, $price, '$image', '$status')";

    $data = mysqli_query($conn, $sql);

    if ($data) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product.";
    }

    header("Location: ../SellerProduct.php");
    exit();

    $conn->close();
} 

//update
if(isset($_POST['update'])){
    $ProductID = $_POST['ProductID'];
    $user = $_SESSION['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $stocks = $_POST['stocks'];
    
    // Initialize $imagePath for file upload
    $imagePath = '';

    // Handle file upload if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $imagePath = uniqid() . "_" . basename($image);
        $target_file = $target_dir . $imagePath;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo json_encode(['success' => false, 'error' => 'File upload failed']);
            exit;
        }
    }

    // Construct SQL query to update resident details
    $sql = "UPDATE product SET
            Category = '$category',
            Description = '$description',
            Discount = '$discount',
            Stocks = '$stocks',
            Price = '$price'";

    if ($imagePath) {
        $sql .= ", image = '$imagePath'";
    }

    $sql .= " WHERE ProductID = '$ProductID'";
    // Execute the query

    $data = mysqli_query($conn, $sql);

    if ($data) {
        $_SESSION['success'] = "Product  updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product.";
    }

    header("Location: ../SellerProduct.php");
    exit();

    $conn->close();

}

//delete
if (isset($_POST['delete']) && isset($_POST['ProductID'])) {
    $ProductID = $_POST['ProductID'];

    $sql = "UPDATE product SET status = 'Deleted' WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ProductID);

    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Failed to delete product.";
    }

    $stmt->close();
    $conn->close();
    exit();
}

?>