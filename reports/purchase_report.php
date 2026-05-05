<?php
include "../includes/init.php";
include "../layout/header.php";
/** @var mysqli $conn */

// Initialize Date
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Fetch Data
$query = "SELECT p.*, s.name AS supplier_name
FROM purchases p
JOIN suppliers s ON
p.supplier_id = s.id ";

// Add Date Filter
$params = [];
$types = '';

if ($from && $to) {
    $query .= "WHERE DATE(p.created_at) BETWEEN ? AND ? ";
    $params[] = $from;
    $params[] = $to;
    $types .= "ss";
}

$query .= "ORDER BY p.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-3">Purchase Report</h3>
        <a href="purchase_report_pdf.php" target="_blank" class="btn btn-danger">
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

<!-- Purchase Table -->
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-warning">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                    <td><?= e($row['supplier_name']); ?></td>
                    <td><?= e($row['item_name']); ?></td>
                    <td><?= e($row['quantity']); ?></td>
                    <td><?= number_format($row['rate'], 2); ?></td>
                    <td><?= number_format($row['total'], 2); ?></td>
                    <td><?= number_format($row['paid'], 2); ?></td>
                    <td><?= ucfirst($row['remaining']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include "../layout/footer.php"; ?>