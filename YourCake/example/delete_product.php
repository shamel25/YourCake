<?php
include("../db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ProductID'];   

    $sql = "UPDATE product SET status = 'Deleted' WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ProductID'];

    // Get current status
    $sql = "SELECT status FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    // Decide the new status
    if ($currentStatus === 'Deleted') {
        $newStatus = 'Available';
    } else {
        $newStatus = 'Deleted';
    }

    // Update the status
    $updateSql = "UPDATE product SET status = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newStatus, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}*/

?>