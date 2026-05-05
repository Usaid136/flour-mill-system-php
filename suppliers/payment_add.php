<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */

$supplier_id = $_GET['supplier_id'];

// Fetch supplier
$stmt = mysqli_prepare($conn, "SELECT * FROM suppliers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $supplier_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$supplier = mysqli_fetch_assoc($result);

?>

<h1>Add Payment For <?= e($supplier['name']); ?></h1>
<!-- Form -->
<div class="card col-md-6 shadow-sm">
    <div class="card-body">
        <form action="payment_add_process.php" method="post">
            <input type="hidden" class="form-control" value="<?= $supplier['id']; ?>" name="supplier_id">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Amount</label>
                <input type="number" class="form-control" name="amount" id="exampleFormControlInput1" placeholder="Enter amount">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description">
            </div>
            <div class="mb-3">
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Save Payment
                </button>
                <a href="suppliers_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php include "../layout/footer.php"; // Include Footer 
?>