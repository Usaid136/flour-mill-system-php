<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php"; // Include Header File
/** @var mysqli $conn */

if (!isset($_GET['supplier_id'])) {
    redirect('suppliers_list.php');
}

$supplier_id = $_GET['supplier_id'];


// Fetch supplier
$stmt = mysqli_prepare($conn, "SELECT * FROM suppliers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $supplier_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$supplier = mysqli_fetch_assoc($result);

// Fetch all purchases
$stmtPurchases = mysqli_prepare($conn, "
    SELECT created_at, item_name, quantity, rate, total, paid, remaining 
    FROM purchases 
    WHERE supplier_id = ?
    ORDER BY created_at ASC
");
mysqli_stmt_bind_param($stmtPurchases, "i", $supplier_id);
mysqli_stmt_execute($stmtPurchases);
$purchases = mysqli_stmt_get_result($stmtPurchases);

// Fetch all payments
$stmtPayments = mysqli_prepare($conn, "
    SELECT created_at, amount, description 
    FROM supplier_payments 
    WHERE supplier_id = ?
    ORDER BY created_at ASC
");
mysqli_stmt_bind_param($stmtPayments, "i", $supplier_id);
mysqli_stmt_execute($stmtPayments);
$payments = mysqli_stmt_get_result($stmtPayments);

?>

<h3 class="mb-3">Supplier Ledger</h3>

<div class="card mb-4">
    <div class="card-body">
        <div>
            <h5><?= e($supplier['name']); ?></h5>
            <p class="mb-1">Phone: <?= e($supplier['phone']); ?></p>
            <p>Address: <?= e($supplier['address']); ?></p>
        </div>
        <div>
            <a href="payment_add.php?supplier_id=<?= $supplier['id']; ?>"
                class="btn btn-success">
                <i class="fas fa-plus"></i> Add Payment
            </a>
        </div>
    </div>
</div>

<!-- Ledger Purchase Table -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-warning">
            <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Quantity (KG)</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $finalBalance = 0;
            while ($row = mysqli_fetch_assoc($purchases)):
            ?>
                <tr>
                    <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                    <td><?= $row['item_name']; ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td><?= $row['rate']; ?></td>
                    <td><?= $row['total']; ?></td>
                    <td><?= $row['paid']; ?></td>
                    <td><strong><?= number_format($row['remaining'], 2); ?></strong></td>
                </tr>
                <?php $finalBalance += $row['remaining']; ?>
            <?php endwhile; ?>
            <tr class="table-dark">
                <td colspan="6" class="text-end"><strong>Final Balance</strong></td>
                <td><strong><?= number_format($finalBalance, 2); ?></strong></td>
            </tr>

        </tbody>
    </table>
</div>

<!-- Ledger Payment Table -->
<h4 class="mt-4">Payments</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-info">
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalPayments = 0;
            while ($pay = mysqli_fetch_assoc($payments)):
                $totalPayments += $pay['amount'];
            ?>
                <tr>
                    <td><?= date("d-m-Y", strtotime($pay['created_at'])); ?></td>
                    <td><?= number_format($pay['amount'], 2); ?></td>
                    <td><?= e($pay['description']); ?></td>
                </tr>
            <?php endwhile; ?>
            <tr class="table-dark">
                <td class="text-end"><strong>Total Paid</strong></td>
                <td><strong><?= number_format($totalPayments, 2); ?></strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>