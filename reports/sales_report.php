<?php
include "../includes/init.php";
include "../layout/header.php";
/** @var mysqli $conn */

// Initialize Date
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Fetch Data
$query = "
SELECT s.*, c.name AS customer_name, p.name AS product_name
FROM sales s 
LEFT JOIN customers c ON s.customer_id = c.id
LEFT JOIN products p ON s.product_id = p.id ";

// Add Date Filter
$params = [];
$types = '';

if ($from && $to) {
    $query .= "WHERE DATE(s.created_at) BETWEEN ? AND ? ";
    $params[] = $from;
    $params[] = $to;
    $types .= "ss";
}

$query .= "ORDER BY s.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-3">Sales Report</h3>
        <a href="sales_report_pdf.php" target="_blank" class="btn btn-danger">
            <i class="fa fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Filter Form -->
<form method="get" class="row g-3 mb-3">
    <div class="col-md-3">
        <input type="date" class="form-control" name="from" value="<?= e($from) ?>" placeholder="From Date">
    </div>
    <div class="col-md-3">
        <input type="date" class="form-control" name="to" value="<?= e($to) ?>" placeholder="To Date">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="sales_report.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Sales Table -->
 <div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="table-warning">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Payment Type</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        $totalSales = 0;
        $totalCash = 0;
        $totalCredit = 0; ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $totalSales += $row['total'];
            if ($row['payment_type'] == 'cash') $totalCash += $row['total'];
            else $totalCredit += $row['total'];
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                <td><?= e($row['customer_name'] ?: 'Cash Customer'); ?></td>
                <td><?= e($row['product_name']); ?></td>
                <td><?= $row['quantity']; ?></td>
                <td><?= number_format($row['rate'], 2); ?></td>
                <td><?= number_format($row['total'], 2); ?></td>
                <td>
                    <span class="badge <?= ($row['payment_type'] == 'cash') ? 'bg-success' : 'bg-warning' ?>">
                        <?= ($row['payment_type'] == 'cash')
                            ? '<i class="fa-solid fa-money-bill-wave me-1"></i> Cash'
                            : '<i class="fa-solid fa-file-invoice-dollar me-1"></i> Credit'; ?>
                    </span>
                </td>
            </tr>
        <?php endwhile; ?>
        <tr class="table-dark">
            <td colspan="7" class="text-end"><strong>Total Sales</strong></td>
            <td colspan="2"><strong><?= number_format($totalSales, 2); ?></strong></td>
        </tr>
        <tr class="table-light">
            <td colspan="7" class="text-end"><strong>Total Cash</strong></td>
            <td colspan="2"><strong><?= number_format($totalCash, 2); ?></strong></td>
        </tr>
        <tr class="table-light">
            <td colspan="7" class="text-end"><strong>Total Credit</strong></td>
            <td colspan="2"><strong><?= number_format($totalCredit, 2); ?></strong></td>
        </tr>
    </tbody>
</table>
</div>

<?php include "../layout/footer.php"; ?>