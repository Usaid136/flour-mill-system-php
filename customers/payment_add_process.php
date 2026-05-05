<?php
include "../includes/init.php"; // Include Init File

/** @var mysqli $conn */


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = c($_POST['customer_id']);
    $amount = c($_POST['amount']);
    $description = c($_POST['description'] ?? 'Payment');

    // Input Validation
    if (!required($customer_id) || $amount <= 0) {
        setFlash("error", "Please select customer and enter valid amount.");
        redirect('payment_add.php');
    }

    // Insert Customer
    $stmt = mysqli_prepare($conn, "INSERT INTO transactions 
    (customer_id, type, amount, description) VALUES (
    ?,?,?,?);
    ");
    $type = "credit";  // Customer is paying money
    mysqli_stmt_bind_param($stmt, "isds", $customer_id,$type, $amount, $description);
    mysqli_stmt_execute($stmt);
    setFlash('success', 'Payment recorded successfully.');
    redirect("customer_ledger.php?id=$customer_id");
}
