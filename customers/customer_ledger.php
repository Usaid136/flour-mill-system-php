<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php"; // Include Header File

/** @var mysqli $conn */

if (!isset($_GET['id'])) {
    redirect('customer_list.php');
}

$id = $_GET['id'];


// Get Customer
$stmt = mysqli_prepare($conn, "SELECT * FROM customers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    setFlash('error', 'Customer not found.');
    redirect('customer_list.php');
}


// Get Transaction
$stmt2 = mysqli_prepare($conn, "
SELECT * FROM transactions 
WHERE customer_id = ?
ORDER BY created_at ASC
");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$transactions = mysqli_stmt_get_result($stmt2);

?>

<h3 class="mb-3">Customer Ledger</h3>

<div class="card mb-4">
    <div class="card-body">
        <div>
            <h5><?= e($customer['name']); ?></h5>
            <p class="mb-1">Phone: 0<?= e($customer['phone']); ?></p>
            <p>Address: <?= e($customer['address']); ?></p>
        </div>
        <div>
            <a href="payment_add.php?customer_id=<?= $customer['id']; ?>"
                class="btn btn-success">
                <i class="fas fa-plus"></i> Add Payment
            </a>
        </div>
    </div>
</div>

<!-- Ledger Table -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-warning">
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $balance = 0;

            while ($row = mysqli_fetch_assoc($transactions)):

                if ($row['type'] == 'debit') {
                    $balance += $row['amount'];
                    $debit = $row['amount'];
                    $credit = '';
                } else {
                    $balance -= $row['amount'];
                    $debit = '';
                    $credit = $row['amount'];
                }
            ?>

                <tr>
                    <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                    <td><?= $row['description']; ?></td>
                    <td><?= $debit; ?></td>
                    <td><?= $credit; ?></td>
                    <td><strong><?= number_format($balance, 2); ?></strong></td>
                </tr>

            <?php endwhile; ?>
            <tr class="table-dark">
                <td colspan="4" class="text-end"><strong>Final Balance</strong></td>
                <td><strong><?= number_format($balance, 2); ?></strong></td>
            </tr>

        </tbody>
    </table>
</div>