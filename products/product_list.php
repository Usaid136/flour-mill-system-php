<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */



// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch Customers
$stmt = mysqli_prepare($conn, "
SELECT * FROM products 
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Count Customers For Pagination
$stmt2 = mysqli_prepare($conn, "
SELECT COUNT(*) FROM products
");
mysqli_stmt_execute($stmt2);
mysqli_stmt_bind_result($stmt2, $total_products);
mysqli_stmt_fetch($stmt2);
mysqli_stmt_close($stmt2);

$total_products_pages = ceil($total_products / $limit);
?>


<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Products List</h1>
        <a href="product_add.php" class="btn btn-primary">
            <i class="fa fa-add"></i> Add Product
        </a>
    </div>
</div>
<!-- Product List -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Stock per kg</th>
                        <th scope="col">Rate per kg</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1 + $offset; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <th scope="row"><?= $counter; ?></th>
                            <td><?= $row['name']; ?></td>
                            <td class="<?= ($row['stock_kg'] < 10) ? 'text-danger' : 'text-success' ?>"><?= $row['stock_kg']; ?></td>
                            <td><?= $row['rate_per_kg']; ?></td>
                            <td>
                                <a href="product_edit.php?id=<?= $row['id']; ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="product_delete.php?id=<?= $row['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
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
                                                    else echo '?page=' . ($page - 1); ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_products_pages; $i++): ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_products_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page >= $total_products_pages) echo '#';
                                                    else echo '&page=' . ($page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>



<?php include "../layout/footer.php"; // Include Footer 
?>