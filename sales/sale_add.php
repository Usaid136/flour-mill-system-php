<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header
/** @var mysqli $conn */

// Fetch Customers
$stmt = mysqli_prepare($conn, "SELECT * FROM customers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Fetch Products
$stmt2 = mysqli_prepare($conn, "SELECT * FROM products");
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
?>


<h1>Add Sale</h1>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="post" action="sale_add_process.php">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">Cash Customer</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>

                            <option value="<?= $row['id'] ?>">
                                <?= e($row['name']) ?>
                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product</label>
                    <select name="product_id" id="product" class="form-select" required>

                        <option value="">Select Product</option>

                        <?php
                        while ($row2 = mysqli_fetch_assoc($result2)):
                        ?>

                            <option data-rate="<?= $row2['rate_per_kg'] ?>"
                                value="<?= $row2['id'] ?>">
                                <?= e($row2['name']) ?>
                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" step="0.01" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rate</label>
                    <input type="number" step="0.01" id="rate" name="rate" class="form-control" placeholder="Enter rate" readonly required>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total</label>
                    <input type="float" id="total" name="total" class="form-control" placeholder="Total amount" readonly required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Save Sale
                </button>

                <a href="sale_list.php" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../layout/footer.php"; ?>