<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

$id = $_GET['id'] ?? 0;

// Check if purchases exist
$stmt = mysqli_prepare($conn, "
    SELECT COUNT(*) as total 
    FROM purchases 
    WHERE supplier_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    setFlash('error', 'Cannot delete supplier. Purchase exist.');
    redirect('suppliers_list.php');
}

// If no transactions -> delete supplier
$stmt = mysqli_prepare($conn, "
    DELETE FROM suppliers WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    setFlash('success', 'Supplier deleted successfully.');
} else {
    setFlash('error', 'Something went wrong.');
}

redirect('suppliers_list.php');
