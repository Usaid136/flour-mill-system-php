<?php

include "../includes/init.php";
/** @var mysqli $conn */
require_once('../tcpdf/tcpdf.php');

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Start HTML
$html = <<<EOD
<h2 style="text-align:center;">Sales Report</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr style="background-color:#4CAF50; color:white; text-align:center;">
        <th>Date</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Total</th>
    </tr>
EOD;

// Fetch sales data
$stmt = mysqli_prepare($conn,"
    SELECT s.created_at, c.name AS customer, p.name AS product, s.total
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products p ON s.product_id = p.id
    ORDER BY s.created_at DESC
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$alternate = false;
while($row = mysqli_fetch_assoc($result)){
    $bgColor = $alternate ? '#f2f2f2' : '#ffffff';
    $html .= "
    <tr style='background-color:{$bgColor};'>
        <td>".date("d-m-Y", strtotime($row['created_at']))."</td>
        <td>".$row['customer']."</td>
        <td>".$row['product']."</td>
        <td>".$row['total']."</td>
    </tr>";
    $alternate = !$alternate;
}

$html .= "</table>";

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("sales_report.pdf", "I");