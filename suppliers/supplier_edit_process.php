<?php
include "../includes/init.php"; // Include Init File
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = c($_POST['id']);
    $supplier_name = c($_POST['supplier_name']);
    $phone = c($_POST['phone']);
    $address = c($_POST['address']);

    // Input Validation
    if (!required($supplier_name) || !required($phone) || !required($address)) {
        setFlash("error", "supplier name, phone number, and address are required.");
        redirect("supplier_edit.php?id=$id");
    }
    // Phone Number Min
    if (!minlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("supplier_edit.php?id=$id");
    }
    // Phone Number Max
    if (!maxlength($phone, 11)) {
        setFlash('error', 'Please enter a valid phone number.');
        redirect("supplier_edit.php?id=$id");
    }

    // Insert Supplier
    $stmt = mysqli_prepare($conn, "
    UPDATE suppliers 
    SET name = ?, phone = ?, address = ?
    WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, "sisi", $supplier_name, $phone, $address, $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        setFlash('success', 'Supplier updated successfully.');
        redirect('suppliers_list.php');
    } else {
        setFlash('error', 'Something went wrong. Please try again.');
        redirect("supplier_edit.php?id=$id");
    }
}
