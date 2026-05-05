<?php
include "../includes/init.php"; // Include Init File
include "../layout/header.php";  // Include Header
/** @var mysqli $conn */
?>

<h1>Add Product</h1>
<!-- Form -->
<div class="card">
    <div class="card-body">
        <form action="product_add_process.php" method="post">
            <div class="col">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="product_name" placeholder="Enter product name">
            </div>
            <div class="row mt-2">
                <div class="col col-md-6 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Stock (KG)</label>
                    <input type="number" step="0.01" class="form-control" name="stock_kg" id="exampleFormControlInput1" placeholder="Enter stock per kg">
                </div>
                <div class="col col-md-6 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Rate (KG)</label>
                    <input type="number" step="0.01" class="form-control" name="rate_per_kg" id="exampleFormControlInput1" placeholder="Enter rate per kg">
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Save Product
                </button>
                <a href="product_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php include "../layout/footer.php"; // Include Footer 
?>