<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = c($_POST['customer_id']);
    $type = c($_POST['type']);
    $amount = c($_POST['amount']);
    $description = c($_POST['description']);

    // Input Validation
    if (!required($customer_id) || !required($type) || !required($amount)) {
        setFlash("error", "Customer name, type, and amount are required.");
        redirect('transaction_add.php');
    }
    // Amount Number
    if (!is_numeric($amount)) {
        setFlash('error', 'Amount field must be a number.');
        redirect('transaction_add.php');
    }
    // Amount Number Min
    if (!minlength($amount, 1)) {
        setFlash('error', 'Please enter a valid amount number.');
        redirect("transaction_add.php");
    }

    // Insert Customer
    $stmt = mysqli_prepare($conn, "INSERT INTO transactions 
    (customer_id,type,amount,description) VALUES (
    ?,?,?,?);
    ");
    mysqli_stmt_bind_param($stmt, "isis", $customer_id, $type, $amount, $description);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        setFlash('success', 'Transaction added successfully.');
        redirect('transaction_list.php');
    } else {
        setFlash('error', 'Something went wrong. Please try again.');
        redirect('transaction_add.php');
    }
}
