<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

$id = $_GET['id'] ?? '';

// Get Sale Detail
$stmt = mysqli_prepare($conn,"SELECT * FROM sales WHERE id = ?");
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$sale = mysqli_fetch_assoc($result);

if(!$sale){
    setFlash('error','Sale not found.');
    redirect('sale_list.php');
}

//  Restore product stock
if ($sale['bag_type'] == '5kg') {
    $stmt = mysqli_prepare($conn, "UPDATE products SET stock_5kg = stock_5kg + ? WHERE id = ?");
} else {
    $stmt = mysqli_prepare($conn, "UPDATE products SET stock_10kg = stock_10kg + ? WHERE id = ?");
}
mysqli_stmt_bind_param($stmt, "ii", $sale['quantity'], $sale['product_id']);
mysqli_stmt_execute($stmt);

// Delete sale record
$stmt = mysqli_prepare($conn, "DELETE FROM sales WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    setFlash('error', 'Failed to delete sale.');
    redirect('sale_list.php');
}

// Remove customer ledger transaction if it was credit
if ($sale['payment_type'] == 'credit' && $sale['customer_id']) {
    $stmt = mysqli_prepare($conn, "
        DELETE FROM transactions 
        WHERE customer_id = ? 
        AND type = 'debit' 
        AND amount = ? 
        AND description LIKE ?
        LIMIT 1
    ");
    $desc = "Sale of {$sale['quantity']} {$sale['bag_type']} bag(s)";
    mysqli_stmt_bind_param($stmt, "ids", $sale['customer_id'], $sale['total'], $desc);
    mysqli_stmt_execute($stmt);
}

// Success message
setFlash('success', 'Sale deleted successfully.');
redirect('sale_list.php');