<?php
include "../includes/init.php";
include "../layout/header.php";
/** @var mysqli $conn */


$id = $_GET['id'] ?? 0;

// Check if transactions exist
$stmt = mysqli_prepare($conn, "
    SELECT COUNT(*) as total 
    FROM transactions 
    WHERE customer_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    setFlash('error', 'Cannot delete customer. Transactions exist.');
    redirect('customer_list.php');
}

// If no transactions -> delete customer
$stmt = mysqli_prepare($conn, "
    DELETE FROM customers WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    setFlash('success', 'Customer deleted successfully.');
} else {
    setFlash('error', 'Something went wrong.');
}

redirect('customer_list.php');
