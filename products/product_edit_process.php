<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];
    $product_name = c($_POST['product_name']);
    $stock_kg = c($_POST['stock_kg']);
    $rate_per_kg = c($_POST['rate_per_kg']);

    // Input Validation
    if (!required($product_name) || !required($stock_kg) || !required($rate_per_kg)) {
        setFlash("error", "All fields are required. Please fill in product name, stock, and rates.");
        redirect("product_edit.php?id=$id");
    }
    // Stock Number
    if (!is_numeric($stock_kg)) {
        setFlash('error', 'Stock field must be a number.');
        redirect("product_edit.php?id=$id");
    }
    // Rate Number
    if (!is_numeric($rate_per_kg)) {
        setFlash('error', 'Rate field must be a number.');
        redirect("product_edit.php?id=$id");
    }
    // Stock cannot be negative
    if ($stock_kg < 0) {
        setFlash('error', 'Stock cannot be negative. Please enter a value of 0 or higher for stock per kg.');
        redirect("product_edit.php?id=$id");
    }
    // Rate cannot be negative
    if ($rate_per_kg < 0) {
        setFlash('error', 'Rate cannot be negative. Please enter a value of 0 or higher for rates per kg.');
        redirect("product_edit.php?id=$id");
    }


    // Insert product
    $stmt = mysqli_prepare($conn, "UPDATE products 
    SET name = ?,stock_kg = ?,rate_per_kg = ? 
    WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, "sddi", $product_name, $stock_kg, $rate_per_kg,$id);
    mysqli_stmt_execute($stmt);
    setFlash('success', 'Product updated successfully.');
    redirect('product_list.php');
}
