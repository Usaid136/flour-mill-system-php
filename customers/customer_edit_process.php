<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php"; // Include Header File

/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = c($_POST['id']);
    $customer_name = c($_POST['customer_name']);
    $phone = c($_POST['phone']);
    $address = c($_POST['address']);

    // Input Validation
    if (!required($customer_name) || !required($phone) || !required($address)) {
        setFlash("error", "Customer name, phone number, and address are required.");
        redirect('customer_edit.php');
    }
    // Phone Number Min
    if (!minlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("customer_edit.php");
    }
    // Phone Number Max
    if (!maxlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("customer_edit.php");
    }

    // Insert Customer
    $stmt = mysqli_prepare($conn, "
    UPDATE customers 
    SET name = ?, phone = ?, address = ?
    WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, "sisi", $customer_name, $phone, $address, $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        setFlash('success', 'Customer updated successfully.');
        redirect('customer_list.php');
    } else {
        setFlash('error', 'Something went wrong. Please try again.');
        redirect('customer_edit.php');
    }
}
