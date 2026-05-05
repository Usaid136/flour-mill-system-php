<?php
include "../includes/init.php";
include "../layout/header.php";
/** @var mysqli $conn */

if (!isset($_GET['id'])) {
    redirect('sale_list.php');
}

$id = $_GET['id'];
// Fetch Sale
$stmt = mysqli_prepare($conn, "
SELECT c.name AS customer_name,
p.name AS product_name, s.id, s.quantity,
s.rate, s.total, s.payment_type, s.created_at 
FROM sales s
JOIN customers c ON s.customer_id = c.id
JOIN products p ON s.product_id = p.id
WHERE s.id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

?>

<div class="container mt-4">

    <h3 class="mb-4">Sale Details</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Sale ID</th>
                        <td><?= $row['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td><?= $row['customer_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <td><?= $row['product_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td><?= $row['quantity']; ?></td>
                    </tr>
                    <tr>
                        <th>Rate</th>
                        <td>Rs <?= $row['rate']; ?></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td><strong>Rs <?= $row['total']; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Payment Type</th>
                        <td>
                            <span class="badge <?= ($row['payment_type'] == 'cash') ? 'bg-success' : 'bg-warning' ?>">
                                <?= ($row['payment_type'] == 'cash')
                                    ? '<i class="fa-solid fa-money-bill-wave me-1"></i> Cash'
                                    : '<i class="fa-solid fa-file-invoice-dollar me-1"></i> Credit'; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                    </tr>
                </table>

                <div class="mt-3">
                    <button class="btn btn-primary">
                        Print Invoice
                    </button>
                    <a href="sale_list.php" class="btn btn-secondary">
                        Back
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>