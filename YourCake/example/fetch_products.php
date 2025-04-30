<?php
include("../db_connection.php");
$user = "shamel"; // OR you can use session if login is active

$sql = "SELECT * FROM product WHERE Seller_Username = '$user' and status = 'Available' ORDER BY ProductID DESC";
$result = $conn->query($sql);

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
    echo "<tr><td colspan='9'>No products found</td></tr>";
}
?>