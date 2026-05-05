<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = c($_POST['customer_id']) ?: null;
    $product_id = c($_POST['product_id']);
    $quantity = (float) c($_POST['quantity']);
    $rate = (float) c($_POST['rate']);
    $total = (float) c($_POST['total']);
    $payment_type = c($_POST['payment_type']);

    // Input Validation
    if (!required($product_id) || $quantity <= 0 || $rate <= 0 || $total <= 0 || !required($payment_type)) {
        setFlash("error", "All fields are required or invalid!");
        redirect('sale_add.php');
    }


    // Check Stock
    $stmt  = mysqli_prepare($conn, "SELECT stock_kg FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        setFlash('error', 'Product not found.');
        redirect('sale_add.php');
    }
    if ($quantity > $product['stock_kg']) {
        setFlash('error', 'Not enough stock available.');
        redirect('sale_add.php');
    }

    // Deduct Stock
    $stmt = mysqli_prepare($conn, "UPDATE products SET stock_kg = stock_kg - ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $product_id);
    mysqli_stmt_execute($stmt);


    // Insert sale
    $stmt = mysqli_prepare($conn, "INSERT INTO sales 
    (customer_id, product_id, quantity, rate, total, payment_type) VALUES (
    ?,?,?,?,?,?)
    ");
    mysqli_stmt_bind_param($stmt, "iiddds", $customer_id, $product_id, $quantity, $rate, $total, $payment_type);
    mysqli_stmt_execute($stmt);

    // Update Customer Ledger If Credit
    if ($payment_type == "credit" && $customer_id) {
        $description = "Sale of {$quantity} KG(s)";
        $stmt = mysqli_prepare($conn, "INSERT INTO transactions (customer_id, type, amount, description) VALUES (?,?,?,?)");
        $type = "debit";  // Customer owes money
        mysqli_stmt_bind_param($stmt, "isds", $customer_id, $type, $total, $description);
        mysqli_stmt_execute($stmt);
    }

    setFlash('success', 'Sale recorded successfully!');
    redirect('sale_list.php');
} else {
    setFlash('error', 'Something went wrong. Please try again.');
    redirect('sale_add.php');
}
