<?php
include "../includes/init.php";
include "../layout/header.php";
/** @var mysqli $conn */

// Fetch Sale
$stmt = mysqli_prepare($conn, "
SELECT 
COALESCE(c.name,'Cash Customer') AS customer_name,
p.name AS product_name,
s.id,
s.quantity,
s.rate,
s.total,
s.payment_type,
s.created_at
FROM sales s
LEFT JOIN customers c ON s.customer_id = c.id
JOIN products p ON s.product_id = p.id
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Sales List</h1>

        <a href="sale_add.php" class="btn btn-primary">
            <i class="fa fa-add"></i> Add Sale
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead class="table-warning">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Qty (KG)</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['customer_name']; ?></td>
                                <td><?= $row['product_name']; ?></td>
                                <td><?= $row['quantity']; ?></td>
                                <td><?= $row['rate']; ?></td>
                                <td><?= number_format($row['total'], 2); ?></td>
                                <td>
                                    <span class="badge <?= ($row['payment_type'] == 'cash') ? 'bg-success' : 'bg-warning' ?>">
                                        <?= ($row['payment_type'] == 'cash')
                                            ? '<i class="fa-solid fa-money-bill-wave me-1"></i> Cash'
                                            : '<i class="fa-solid fa-file-invoice-dollar me-1"></i> Credit'; ?>
                                    </span>
                                </td>
                                <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="sale_view.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="sale_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this sale?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<?php
include "../layout/footer.php";
?>