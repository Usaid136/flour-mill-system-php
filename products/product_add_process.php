<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $product_name = c($_POST['product_name']);
    $stock_kg = c($_POST['stock_kg']);
    $rate_per_kg = c($_POST['rate_per_kg']);

    // Input Validation
    if (!required($product_name) || !required($stock_kg) || !required($rate_per_kg)) {
        setFlash("error", "All fields are required. Please fill in product name, stock, and rates.");
        redirect('product_add.php');
    }
    // Stock Number
    if (!is_numeric($stock_kg)) {
        setFlash('error', 'Stock field must be a number.');
        redirect('product_add.php');
    }
    // Stock Number
    if (!is_numeric($rate_per_kg)) {
        setFlash('error', 'Rate field must be a number.');
        redirect('product_add.php');
    }
    // Stock cannot be negative
    if ($stock_kg < 0) {
        setFlash('error', 'Stock cannot be negative. Please enter a value of 0 or higher for stock.');
        redirect('product_add.php');
    }
    // Rate cannot be negative
    if ($rate_per_kg < 0) {
        setFlash('error', 'Rate cannot be negative. Please enter a value of 0 or higher for rates.');
        redirect('product_add.php');
    }


    // Insert product
    $stmt = mysqli_prepare($conn, "INSERT INTO products 
    (name,stock_kg,rate_per_kg) VALUES (
    ?,?,?)
    ");
    mysqli_stmt_bind_param($stmt, "sdd", $product_name, $stock_kg, $rate_per_kg);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        setFlash('success', 'Product added successfully.');
        redirect('product_list.php');
    } else {
        setFlash('error', 'Something went wrong. Please try again.');
        redirect('product_add.php');
    }
}
