<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header

/** @var mysqli $conn */

// Fetch Customer
$stmt = mysqli_prepare($conn, "SELECT * FROM customers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<h1>Add Payment</h1>
<!-- Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="payment_add_process.php" method="post">
            <div class="mb-3">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select">
                    <option value="" selected disabled>Select Customer</option>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <option value="<?= $row['id'] ?>">
                            <?= e($row['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
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
                <a href="customer_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>



        <?php include "../layout/footer.php"; // Include Footer 
        ?>