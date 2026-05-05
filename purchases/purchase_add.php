<?php
include "../includes/init.php";
include "../layout/header.php";

/** @var mysqli $conn */
// Fetch Suppliers
$stmt  = mysqli_prepare($conn, "SELECT * FROM suppliers");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<h3 class="mb-3">Add Purchase</h3>

<div class="card">
    <div class="card-body">

        <form action="purchase_add_process.php" method="POST">
            <div class="row">
                <div class="col col-md-6 mb-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option selected disabled>Select Supplier Name</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <option value="<?= $row['id'] ?>">
                                <?= e($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col col-md-6 mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-4 mb-3">
                    <label class="form-label">Quantity (KG)</label>
                    <input type="number" step="0.01" name="quantity" class="form-control" required>
                </div>

                <div class="col col-md-4 mb-3">
                    <label class="form-label">Rate</label>
                    <input type="number" step="0.01" name="rate" class="form-control" required>
                </div>

                <div class="col col-md-4 mb-3">
                    <label class="form-label">Paid Amount</label>
                    <input type="number" step="0.01" name="paid" value="0" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 mb-3">
                    <label class="form-label">Total</label>
                    <input type="number" step="0.01" name="total" class="form-control" readonly>
                </div>

                <div class="col col-md-6 mb-3">
                    <label class="form-label">Remaining</label>
                    <input type="number" step="0.01" name="remaining" class="form-control" readonly>
                </div>
            </div>
            <button class="btn btn-warning">
                <i class="fa fa-save"></i> Save Purchase
            </button>
            <a href="purchases_list.php" class="btn btn-secondary">Cancel</a>

        </form>

    </div>
</div>

<script>
    const qty = document.querySelector('input[name="quantity"]');
    const rate = document.querySelector('input[name="rate"]');
    const total = document.querySelector('input[name="total"]');
    const paid = document.querySelector('input[name="paid"]');
    const remaining = document.querySelector('input[name="remaining"]');

    function calcTotal() {
        const t = (parseFloat(qty.value) || 0) * (parseFloat(rate.value) || 0);
        total.value = t.toFixed(2);
        calcRemaining();
    }

    function calcRemaining() {
        const r = (parseFloat(total.value) || 0) - (parseFloat(paid.value) || 0);
        remaining.value = r.toFixed(2);
    }

    qty.addEventListener("input", calcTotal);
    rate.addEventListener("input", calcTotal);
    paid.addEventListener("input", calcRemaining);
</script>

<?php include "../layout/footer.php"; ?>