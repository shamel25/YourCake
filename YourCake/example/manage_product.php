<?php
include("../db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller = $_POST['seller_username'];
    $prodName = $_POST['prodName'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $stocks = $_POST['stocks'];

    // Image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $imageName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo "Error uploading file.";
            exit;
        }
    } else {
        $imageName = null; // No new image, so don't update the image field
    }

    // Check if it's an Update or Add
    if (!empty($_POST['ProductID'])) {
        $productID = $_POST['ProductID'];

        if ($imageName) {
            // If a new image is uploaded, update the image field
            $sql = "UPDATE product 
                    SET ProductName = ?, Description = ?, Category = ?, Price = ?, Discount = ?, Stocks = ?, Image = ? 
                    WHERE ProductID = ? and Seller_Username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssis", $prodName, $description, $category, $price, $discount, $stocks, $imageName, $productID, $seller);
        } else {
            // If no new image, don't update the image field
            $sql = "UPDATE product 
                    SET ProductName = ?, Description = ?, Category = ?, Price = ?, Discount = ?, Stocks = ? 
                    WHERE ProductID = ? and Seller_Username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssis", $prodName, $description, $category, $price, $discount, $stocks, $productID, $seller);
        }
    
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error updating product: " . $stmt->error;  // Capture the exact error
        }

    } else {
        // Insert new product
        $sql = "INSERT INTO product (Seller_Username, ProductName, Description, Category, Price, Discount, Stocks, Image, Status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Available')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $seller, $prodName, $description, $category, $price, $discount, $stocks, $imageName);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error inserting product: " . $stmt->error;  // Capture the exact error
        }
    }
}
?>