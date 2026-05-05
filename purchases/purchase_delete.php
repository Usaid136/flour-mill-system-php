<?php
include "../includes/init.php";  // Include Init File
/** @var mysqli $conn */


// Get Id
if (!isset($_GET['id'])) {
    redirect('purchases_list.php');
}

$id = $_GET['id'];

// Delete Purchase
$stmt = mysqli_prepare($conn, "DELETE FROM purchases WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
setFlash('success', 'Purchase deleted successfully.');
redirect('purchases_list.php');
