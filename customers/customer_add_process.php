<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_name = c($_POST['customer_name']);
    $phone = c($_POST['phone']);
    $address = c($_POST['address']);

    // Input Validation
    if (!required($customer_name) || !required($phone) || !required($address)) {
        setFlash("error", "All fields are required.");
        redirect('customer_add.php');
    }
    // Phone Number
    if (!is_numeric($phone)) {
        setFlash('error', 'Phone field must be a number.');
        redirect('customer_add.php');
    }
    // Phone Number Min
    if (!minlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("customer_add.php");
    }
    // Phone Number Max
    if (!maxlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("customer_add.php");
    }

    // Insert Customer
    $stmt = mysqli_prepare($conn, "INSERT INTO customers 
    (name,phone,address) VALUES (
    ?,?,?);
    ");
    mysqli_stmt_bind_param($stmt, "sis", $customer_name, $phone, $address);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        setFlash('success', 'Customer added successfully.');
        redirect('customer_list.php');
    } else {
        setFlash('error', 'Something went wrong. Please try again.');
        redirect('customer_add.php');
    }
}
