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

// Fetch suppliers
$stmt = mysqli_prepare($conn, "
SELECT * FROM suppliers 
    WHERE name LIKE ?
    OR phone LIKE ?
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
$searchParam = "%$search%";
mysqli_stmt_bind_param($stmt, "siii", $searchParam, $searchParam, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Count suppliers For Pagination
$stmt2 = mysqli_prepare($conn, "
SELECT COUNT(*) FROM suppliers 
    WHERE name LIKE ?
    OR phone LIKE ?
");
mysqli_stmt_bind_param($stmt2, "ss", $searchParam, $searchParam);
mysqli_stmt_execute($stmt2);
mysqli_stmt_bind_result($stmt2, $total_suppliers);
mysqli_stmt_fetch($stmt2);
mysqli_stmt_close($stmt2);

$total_suppliers_pages = ceil($total_suppliers / $limit);
?>


<h1>Suppliers List</h1>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <form method="get" class="d-flex col-md-4">
            <input class="form-control me-2" name="search" type="search" placeholder="Search by name or phone" value="<?= e($search); ?>" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <a href="supplier_add.php" class="btn btn-success mb-3 btn-md">
            <i class="fa fa-add"></i> Add Supplier
        </a>
    </div>
</div>
<!-- supplier List -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <th scope="row"><?= $counter; ?></th>
                            <td><?= $row['name']; ?></td>
                            <td>0<?= $row['phone']; ?></td>
                            <td><?= $row['address']; ?></td>
                            <td><?= date("d-m-Y", strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="supplier_ledger.php?supplier_id=<?= $row['id']; ?>"
                                    class="btn btn-sm btn-info">
                                    <i class="fa fa-book"></i>
                                </a>
                                <a href="supplier_edit.php?id=<?= $row['id']; ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="supplier_delete.php?id=<?= $row['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
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
                    <?php for ($i = 1; $i <= $total_suppliers_pages; $i++): ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_suppliers_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page >= $total_suppliers_pages) echo '#';
                                                    else echo '?search=' . urlencode($search) . '&page=' . ($page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>




<?php include "../layout/footer.php"; // Include Footer 
?>