<?php
include "../includes/init.php";  // Include Init File
include "../layout/header.php";  // Include Header File
/** @var mysqli $conn */

// Redirect if no ID
if (!isset($_GET['id'])) {
    redirect('purchases_list.php');
}

$id = $_GET['id'];

// Fetch purchase
$stmt = mysqli_prepare($conn, "SELECT * FROM purchases WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$purchase = mysqli_fetch_assoc($result);

// Fetch all suppliers for dropdown
$supplier_stmt = mysqli_prepare($conn, "SELECT * FROM suppliers ORDER BY name ASC");
mysqli_stmt_execute($supplier_stmt);
$suppliers_result = mysqli_stmt_get_result($supplier_stmt);

?>

<h3 class="mb-3">Edit Purchase</h3>

<div class="card">
    <div class="card-body">
        <form action="purchase_edit_process.php" method="POST">
            <input type="hidden" name="id" value="<?= $purchase['id']; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">Select Supplier</option>
                        <?php while($supplier = mysqli_fetch_assoc($suppliers_result)): ?>
                            <option value="<?= $supplier['id']; ?>" <?= $purchase['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>>
                                <?= $supplier['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" value="<?= $purchase['item_name']; ?>" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Quantity (KG)</label>
                    <input type="number" step="0.01" name="quantity" value="<?= $purchase['quantity']; ?>" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Rate</label>
                    <input type="number" step="0.01" name="rate" value="<?= $purchase['rate']; ?>" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Paid Amount</label>
                    <input type="number" step="0.01" name="paid" value="<?= $purchase['paid']; ?>" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total</label>
                    <input type="number" step="0.01" name="total" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
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

    // Calculate on page load
    calcTotal();
</script>

<?php include "../layout/footer.php"; ?>