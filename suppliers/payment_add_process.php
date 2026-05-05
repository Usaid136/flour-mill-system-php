<?php
include "../includes/init.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $supplier_id = (int) $_POST['supplier_id'];
    $amount      = (float) $_POST['amount'];
    $description = c($_POST['description'] ?? 'Supplier Payment');

    if ($amount <= 0) {
        setFlash('error', 'Invalid payment details.');
        redirect("payment_add.php");
    }

    $remaining_payment = $amount;


    $stmt = mysqli_prepare($conn, "
        SELECT id, remaining 
        FROM purchases
        WHERE supplier_id = ? AND remaining > 0
        ORDER BY created_at ASC
    ");

    mysqli_stmt_bind_param($stmt, "i", $supplier_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    // Apply payment to purchases
    while ($row = mysqli_fetch_assoc($result)) {

        if ($remaining_payment <= 0) {
            break;
        }

        $purchase_id = $row['id'];
        $remaining   = $row['remaining'];

        // decide how much to pay
        $pay_amount = ($remaining_payment >= $remaining) ? $remaining : $remaining_payment;

        // update purchase
        $stmt2 = mysqli_prepare($conn, "
        UPDATE purchases
        SET 
        paid = paid + ?, 
        remaining = remaining - ?
        WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt2, "ddi", $pay_amount, $pay_amount, $purchase_id);
        mysqli_stmt_execute($stmt2);

        $remaining_payment -= $pay_amount;
    }

    // Insert payment record in seperate table
    $stmt3 = mysqli_prepare($conn, "INSERT INTO supplier_payments (supplier_id,amount,description) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt3, "ids", $supplier_id, $amount, $description);
    mysqli_stmt_execute($stmt3);


    setFlash('success', 'Payment added successfully.');
    redirect("supplier_ledger.php?supplier_id=$supplier_id");
}
