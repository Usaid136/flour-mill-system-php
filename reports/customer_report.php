<?php
include "../includes/init.php"; // Include Config File
include "../layout/header.php"; // Include Header File
/** @var mysqli $conn */

// Get All Customers
$stmt = mysqli_prepare($conn, "SELECT * FROM customers ORDER BY name ASC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<h3 class="mb-3">Customer Report</h3>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Current Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php while ($customer = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Calculate balance dynamically from transactions
                        $stmt2 = mysqli_prepare($conn, "
            SELECT SUM(CASE WHEN type='debit' THEN amount ELSE 0 END) AS total_debit,
            SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) AS total_credit
            FROM transactions
            WHERE customer_id = ?
            ");
                        mysqli_stmt_bind_param($stmt2, "i", $customer['id']);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
                        $trans = mysqli_fetch_assoc($result2);

                        $currentBalance = ($trans['total_debit'] ?? 0) - ($trans['total_credit'] ?? 0);
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $customer['name']; ?></td>
                            <td>0<?= $customer['phone']; ?></td>
                            <td><?= $customer['address'] ?></td>
                            <td><?= $currentBalance; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../layout/footer.php"; ?>