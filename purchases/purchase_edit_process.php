<?php

include "../includes/init.php";  // Include Init File
/** @var mysqli $conn */  


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];
    $supplier_id = c($_POST['supplier_id']);
    $item_name = c($_POST['item_name']);
    $quantity = c($_POST['quantity']);
    $rate = c($_POST['rate']);
    $paid = c($_POST['paid']);

    // Input Validation
    if (!required($supplier_id) || !required($item_name) || !required($quantity) || !required($rate) || !required($paid)) {
        setFlash('error', 'All fields are required.');
        redirect('purchase_add.php');
    }

    $stmt = mysqli_prepare($conn, "
    UPDATE purchases SET supplier_id = ?, item_name = ?, quantity = ?, rate = ?, total = ?, paid = ?, remaining = ? WHERE id = ?");
    $total = $quantity * $rate;
    $remaining = $total - $paid;
    mysqli_stmt_bind_param($stmt, "ssdddddi", $supplier_id, $item_name, $quantity, $rate, $total, $paid, $remaining, $id);
    mysqli_stmt_execute($stmt);

    setFlash('success', "Purchase updated successfully.");
    redirect('purchases_list.php');
}
