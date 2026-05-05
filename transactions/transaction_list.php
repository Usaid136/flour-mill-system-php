<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */


// Search Input
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch transactions
$stmt = mysqli_prepare($conn, "
SELECT t.id,
    c.name AS customer_name, t.type, 
    t.amount, t.description, t.created_at
FROM transactions t
JOIN customers c ON t.customer_id = c.id
    WHERE c.name LIKE ?
    OR t.type LIKE ? 
    OR t.amount LIKE ?
    OR t.description LIKE ?
    ORDER BY t.created_at DESC
    LIMIT ? OFFSET ?
");
$searchParam = "%$search%";
mysqli_stmt_bind_param($stmt, "ssssii", $searchParam, $searchParam, $searchParam, $searchParam, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Count Transactions For Pagination
$stmt2 = mysqli_prepare($conn, "
SELECT COUNT(*) FROM transactions t 
JOIN customers c ON t.customer_id = c.id
WHERE c.name LIKE ?
    OR t.type LIKE ? 
    OR t.amount LIKE ?
    OR t.description LIKE ?
");
mysqli_stmt_bind_param($stmt2, "ssss", $searchParam, $searchParam, $searchParam, $searchParam);
mysqli_stmt_execute($stmt2);
mysqli_stmt_bind_result($stmt2, $total_trans);
mysqli_stmt_fetch($stmt2);
mysqli_stmt_close($stmt2);

$total_trans_pages = ceil($total_trans / $limit);

?>


<h1>Transactions List</h1>
<!-- transaction List -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <form method="get" class="d-flex col-md-5">
            <input class="form-control me-2" name="search" type="search" placeholder="Search by name, type, amount, description" value="<?= e($search); ?>" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <a href="transaction_add.php" class="btn btn-success btn-md">
            <i class="fa fa-add"></i> Add Transaction
        </a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-warning">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = $offset + 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <th scope="row"><?= $counter; ?></th>
                            <td><?= $row['customer_name']; ?></td>
                            <td><?= $row['type']; ?></td>
                            <td><?= $row['amount']; ?></td>
                            <td><?= $row['description']; ?></td>
                            <td><?= $row['created_at']; ?></td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end mt-2">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page <= 1) echo '#';
                                                    else echo '?search=' . urlencode($search) . '&page=' . ($page - 1); ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_trans_pages; $i++): ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_trans_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page >= $total_trans_pages) echo '#';
                                                    else echo '?search=' . urlencode($search) . '&page=' . ($page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>




<?php include "../layout/footer.php"; // Include Footer 
?>