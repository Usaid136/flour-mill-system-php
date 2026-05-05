<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header
/** @var mysqli $conn */

?>

<h1>Add Customer</h1>
<!-- Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="customer_add_process.php" method="post">
            <div class="row">
                <div class="col col-md-6 mb-3">
                    <label for="name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="name" name="customer_name" placeholder="Enter customer name">
                </div>
                <div class="col col-md-6 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Phone</label>
                    <input type="number" class="form-control" name="phone" id="exampleFormControlInput1" placeholder="Enter customer phone no">
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" placeholder="Enter customer address" name="address" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Save Customer
                </button>
                <a href="customer_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php include "../layout/footer.php"; // Include Footer 
?>