<?php
include "includes/init.php";
include "layout/header.php";

/** @var mysqli $conn */

// Count Total Products
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total_products FROM products");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_products = mysqli_fetch_assoc($result)['total_products'];

// Count Total Customers
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total_customers FROM customers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_customers = mysqli_fetch_assoc($result)['total_customers'];

// Today's Sales
$stmt = mysqli_prepare($conn, "SELECT SUM(total) AS today_sales FROM sales WHERE DATE(created_at) = ?");
$today = date('Y-m-d');
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$today_sale = mysqli_fetch_assoc($result)['today_sales'] ?? 0;

// Total Credit
$stmt = mysqli_prepare($conn, "SELECT
    SUM(CASE WHEN type='debit' THEN amount ELSE 0 END) - 
    SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) AS credit_due
    FROM transactions
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$credit_due = mysqli_fetch_assoc($result)['credit_due'];

// Total Suppliers
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total_suppliers FROM suppliers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_suppliers = mysqli_fetch_assoc($result)['total_suppliers'];

// Today's Purchases
$stmt = mysqli_prepare($conn, "SELECT SUM(total) AS today_purchases FROM purchases WHERE DATE(created_at) = ?");
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$today_purchases = mysqli_fetch_assoc($result)['today_purchases'] ?? 0;

// Supplier Payable
$stmt = mysqli_prepare($conn, "SELECT SUM(remaining) AS total_payable FROM purchases");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalPayable = mysqli_fetch_assoc($result)['total_payable'];

// Low Stock Alert
$stmt = mysqli_prepare($conn, "SELECT name, stock_kg, rate_per_kg FROM products WHERE stock_kg < 10");
mysqli_stmt_execute($stmt);
$lowStock = mysqli_stmt_get_result($stmt);

// Recent Sales
$stmt = mysqli_prepare($conn, "
SELECT s.created_at, p.name AS product_name, c.name AS customer_name, s.total 
FROM sales s
LEFT JOIN products p ON s.product_id = p.id
LEFT JOIN customers c ON s.customer_id = c.id
ORDER BY s.created_at DESC 
LIMIT 5
");
mysqli_stmt_execute($stmt);
$recentSales = mysqli_stmt_get_result($stmt);

?>

<h1 class="mb-3">Dashboard</h1>

<!-- Quick Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #3498db;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-boxes me-2"></i> Total Products</h5>
                <h3><?= e($total_products); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #9b59b6;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-users me-2"></i> Total Customers</h5>
                <h3><?= e($total_customers); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #2ecc71;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-cash-register me-2"></i> Today's Sales</h5>
                <h3>Rs <?= number_format($today_sale ?? 0, 2); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #e74c3c;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-hand-holding-dollar me-2"></i> Credit Due</h5>
                <h3>Rs <?= number_format($credit_due, 2); ?></h3>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #f39c12;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-people-carry-box me-2"></i> Total Suppliers</h5>
                <h3><?= e($total_suppliers); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #2980b9;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-shopping-cart me-2"></i> Today's Purchases</h5>
                <h3><?= number_format($today_purchases ?? 0, 2); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark" style="border-left: 5px solid #f1c40f;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-money-bill-transfer me-2"></i> Supplier Payable</h5>
                <h3><?= e($totalPayable); ?></h3>
            </div>
        </div>
    </div>
</div>
<!-- Low Stock Table -->
<div class="card mb-4">
    <div class="card-header bg-danger text-white">Low Stock Alert</div>
    <div class="card-body">
        <div class="table-responsive">
            <?php if (mysqli_num_rows($lowStock) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Stock (KG)</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($lowStock)): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= e($row['name']); ?></td>
                                <td class="<?= ($row['stock_kg'] < 10) ? 'text-danger' : 'text-success' ?> fw-bold"><?= $row['stock_kg']; ?></td>
                                <td><?= number_format($row['rate_per_kg'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-success">All products have sufficient stock.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Sales Table -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">Recent Sales</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($recentSales)): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                            <td><?= e($row['customer_name']); ?></td>
                            <td><?= e($row['product_name']); ?></td>
                            <td>Rs <?= number_format($row['total'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "layout/footer.php"; ?>