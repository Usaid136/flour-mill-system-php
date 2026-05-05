<?php

include "../includes/init.php";  // Include Init File  
/** @var mysqli $conn */


if ($_SERVER['REQUEST_METHOD'] == "POST") {
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

    $stmt = mysqli_prepare($conn, "INSERT INTO purchases (supplier_id, item_name, quantity, rate, total, paid, remaining) VALUES (?,?,?,?,?,?,?)");
    $total = $quantity * $rate;
    $remaining = $total - $paid;
    mysqli_stmt_bind_param($stmt, "isddddd", $supplier_id, $item_name, $quantity, $rate, $total, $paid, $remaining);
    mysqli_stmt_execute($stmt);

    setFlash('success', "Purchase added successfully.");
    redirect('purchases_list.php');
}
