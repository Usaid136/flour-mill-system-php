<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */

// Fetch All Customers
$stmt = mysqli_prepare($conn, "SELECT * FROM customers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<h1>Add Transaction</h1>
<!-- Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="transaction_add_process.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Customer</label>
                <select id="customer" name="customer_id" class="form-select">
                    <option disabled selected>Select Customer</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?= $row['id']; ?>"><?= e($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="row">
            <div class="col col-md-7 mb-3">
                <label for="type" class="form-label">Select Type</label>
                <select id="type" name="type" class="form-select">
                    <option disabled selected>Select type (debit/credit)</option>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
            <div class="col col-md-5 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="exampleFormControlInput1" placeholder="Enter amount">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Enter description (Optional)" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Save transaction</button>
                <a href="transaction_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>



<?php include "../layout/footer.php"; // Include Footer 
?>