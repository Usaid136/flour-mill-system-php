<?php
$current_page = basename($_SERVER['PHP_SELF']);

// Customer Pages
$customer_pages = ['customer_list.php', 'customer_add.php', 'customer_edit.php', 'customer_ledger.php'];

// Transaction Pages
$transaction_pages = ['transaction_list.php', 'transaction_add.php'];

// Product Pages
$product_pages = ['product_list.php', 'product_edit.php', 'product_delete.php', 'product_add.php'];

// Sale Pages
$sale_pages = ['sale_list.php', 'sale_view.php', 'sale_add.php'];

// Purchases Pages
$suppliers_pages = ['suppliers_list.php', 'supplier_add.php', 'supplier_edit.php'];

// Purchases Pages
$purchases_pages = ['purchases_list.php', 'purchase_edit.php', 'purchase_add.php', 'purchase_ledger.php'];

?>
<!-- Sidebar -->
<div class=" border-end" id="sidebar-wrapper" style="min-height:100vh; width:250px;">

    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>index.php" class="list-group-item list-group-item-action border-top p-3 <?= ($current_page == 'index.php') ? 'active text-white border-dark' : 'list-group-item-light border-light' ?>">
            <i class="fa fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>customers/customer_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $customer_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-users me-2"></i> Customers
        </a>
        <a href="<?= BASE_URL ?>products/product_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $product_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-boxes me-2"></i> Products
        </a>
        <a href="<?= BASE_URL ?>sales/sale_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $sale_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-shopping-cart me-2"></i> Sales
        </a>
        <a href="<?= BASE_URL ?>transactions/transaction_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $transaction_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-exchange-alt me-2"></i> Transactions
        </a>
        <a href="<?= BASE_URL ?>suppliers/suppliers_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $suppliers_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-user-tie me-2"></i> Suppliers
        </a>
        <a href="<?= BASE_URL ?>purchases/purchases_list.php" class="list-group-item list-group-item-action <?= (in_Array($current_page, $purchases_pages)) ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-truck me-2"></i> Purchases
        </a>
        <a href="<?= BASE_URL ?>reports/stock_report.php" class="list-group-item list-group-item-action <?= ($current_page == 'stock_report.php') ? 'active text-white' : 'list-group-item-light' ?> p-3">
            <i class="fa fa-warehouse me-2"></i> Stock Report
        </a>
        <a href="<?= BASE_URL ?>reports/customer_report.php" class="list-group-item list-group-item-action <?= ($current_page == 'customer_report.php') ? 'active text-white border-bottom border-dark' : 'list-group-item-light border-bottom border-light' ?> p-3">
            <i class="fa fa-file-invoice-dollar me-2"></i> Customer Report
        </a>
        <a href="<?= BASE_URL ?>reports/sales_report.php" class="list-group-item list-group-item-action <?= ($current_page == 'sales_report.php') ? 'active text-white border-bottom border-dark' : 'list-group-item-light border-bottom border-light' ?> p-3">
            <i class="fa fa-chart-line me-2"></i> Sales Report
        </a>
        <a href="<?= BASE_URL ?>reports/purchase_report.php" class="list-group-item list-group-item-action <?= ($current_page == 'purchase_report.php') ? 'active text-white border-bottom border-dark' : 'list-group-item-light border-bottom border-light' ?> p-3">
            <i class="fa fa-file-invoice me-2"></i> Purchase Report
        </a>
        <a href="<?= BASE_URL ?>backups/backup_database.php" class="list-group-item list-group-item-action <?= ($current_page == 'backup_database.php') ? 'active text-white border-bottom border-dark' : 'list-group-item-light border-bottom border-light' ?> p-3">
            <i class="fa fa-database me-2"></i> Backup Database
        </a>
        
    </div>
</div>