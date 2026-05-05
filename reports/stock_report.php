<?php
include "../includes/init.php";  // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */

// Fetch Products
$stmt = mysqli_prepare($conn, "SELECT * FROM products ORDER BY name ASC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h3 class="mb-3">Stock Report</h3>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Stock (KG)</th>
                        <th>Rate (KG)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) :
                        // Highlight Low Stock
                        $stockClass = $row['stock_kg'] < 10 ? 'text-danger fw-bold' : '';
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= e($row['name']); ?></td>
                            <td class="<?= $stockClass ?>"><?= e($row['stock_kg']); ?></td>
                            <td><?= number_format($row['rate_per_kg'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../layout/footer.php";  // Include Footer 
?>