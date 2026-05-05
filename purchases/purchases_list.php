<?php

include "../includes/init.php"; // Include Init File
include "../layout/header.php"; // Include Header File
/** @var mysqli $conn */


// Search Input
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch Purchases
$stmt = mysqli_prepare($conn, "
SELECT p.*,s.name AS supplier_name
FROM purchases p
JOIN suppliers s ON
p.supplier_id = s.id
    WHERE s.name LIKE ?
    OR p.item_name LIKE ?
    ORDER BY p.created_at DESC
    LIMIT ? OFFSET ?
");
$searchParam = "%$search%";
mysqli_stmt_bind_param($stmt, "siii", $searchParam, $searchParam, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Count Purchases For Pagination
$stmt2 = mysqli_prepare($conn, "
SELECT COUNT(*) AS total
FROM purchases p
JOIN suppliers s ON p.supplier_id = s.id
WHERE s.name LIKE ?
   OR p.item_name LIKE ?
");
mysqli_stmt_bind_param($stmt2, "ss", $searchParam, $searchParam);
mysqli_stmt_execute($stmt2);
mysqli_stmt_bind_result($stmt2, $total_purchases);
mysqli_stmt_fetch($stmt2);
mysqli_stmt_close($stmt2);

$total_purchases_pages = ceil($total_purchases / $limit);
?>


<h1>Purchases List</h1>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <form method="get" class="d-flex col-md-5">
            <input class="form-control me-2" name="search" type="search" placeholder="Search by supplier name or item name" value="<?= e($search); ?>" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <a href="purchase_add.php" class="btn btn-success mb-3 btn-md">
            <i class="fa fa-add"></i> Add Purchase
        </a>
    </div>
</div>
<!-- Customer List -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Supplier Name</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Quantity (KG)</th>
                        <th scope="col">Rate</th>
                        <th scope="col">Total</th>
                        <th scope="col">Paid</th>
                        <th scope="col">Remaining</th>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $counter = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <th scope="row"><?= $counter; ?></th>
                                <td><?= $row['supplier_name']; ?></td>
                                <td><?= $row['item_name']; ?></td>
                                <td><?= $row['quantity']; ?></td>
                                <td><?= $row['rate']; ?></td>
                                <td><?= $row['total']; ?></td>
                                <td><?= $row['paid']; ?></td>
                                <td><?= $row['remaining']; ?></td>
                                <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="purchase_edit.php?id=<?= $row['id']; ?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="purchase_delete.php?id=<?= $row['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this purchase?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-danger">No Record Found!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
        </div>
        </table>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end mt-2">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="<?php if ($page <= 1) echo '#';
                                                else echo '?search=' . urlencode($search) . '&page=' . ($page - 1); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_purchases_pages; $i++): ?>
                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page >= $total_purchases_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="<?php if ($page >= $total_purchases_pages) echo '#';
                                                else echo '?search=' . urlencode($search) . '&page=' . ($page + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</div>



<?php include "../layout/footer.php"; // Include Footer 
?>