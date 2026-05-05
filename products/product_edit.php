<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */

// Get ID
if (!isset($_GET['id'])) {
    redirect('product_list.php');
}
$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "
SELECT * FROM products WHERE id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);


?>


<h1>Edit Product</h1>
<!-- Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="product_edit_process.php" method="post">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" value="<?= e($row['name']); ?>" name="product_name" placeholder="Enter product name">
            </div>
            <div class="row">
                <div class="col col-md-6 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Stock (KG)</label>
                    <input type="number" step="0.01" class="form-control" value="<?= e($row['stock_kg']); ?>" name="stock_kg" id="exampleFormControlInput1" placeholder="Enter stock">
                </div>
                <div class="col col-md-6 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Rate (KG)</label>
                    <input type="number" step="0.01" class="form-control" name="rate_per_kg" value="<?= e($row['rate_per_kg']); ?>" id="exampleFormControlInput1" placeholder="Enter rate per kg">
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Save Product</button>
                <a href="product_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php include "../layout/footer.php"; // Include Footer 
?>