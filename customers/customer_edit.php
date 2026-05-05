<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header

/** @var mysqli $conn */

// Get ID
if (!isset($_GET['id'])) {
    redirect('customer_list.php');
}
$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "
SELECT * FROM customers WHERE id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

?>


<h1>Edit Customer</h1>
<!-- Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="customer_edit_process.php" method="post">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <div class="row">
            <div class="col col-md-6 mb-3">
                <label for="name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" value="<?= e($row['name']); ?>" id="name" name="customer_name" placeholder="Enter customer name">
            </div>
            <div class="col col-md-6 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Phone</label>
                <input type="number" class="form-control" value="0<?= e($row['phone']); ?>" name="phone" id="exampleFormControlInput1" placeholder="Enter customer phone no">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" placeholder="Enter customer address" name="address" rows="3"><?= e($row['address']); ?></textarea>
            </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Save Customer</button>
                <a href="customer_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>



<?php include "../layout/footer.php"; // Include Footer 
?>