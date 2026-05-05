<?php
include "../includes/init.php"; // Init file
include "../layout/header.php"; // Header
/** @var mysqli $conn */

// Get product ID safely
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if product exists
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    setFlash('error', 'Product not found.');
    redirect('product_list.php');
}

// Check transactions
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM transactions WHERE product_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$totalTransactions = $row['total'];

// Check sales
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM sales WHERE product_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$totalSales = $row['total'];

// Stop deletion if transactions or sales exist
if ($totalTransactions > 0 || $totalSales > 0) {
    setFlash('error', 'Cannot delete product. Transactions or sales exist.');
    redirect('product_list.php');
}

// Delete product
$stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    setFlash('success', 'Product deleted successfully.');
} else {
    setFlash('error', 'Something went wrong. Please try again.');
}

redirect('product_list.php');