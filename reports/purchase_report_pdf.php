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
<h2 style="text-align:center;">Purchases Report</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr style="background-color:#4CAF50; color:white; text-align:center;">
            <th>Date</th>
            <th>Supplier</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Remaining</th>
    </tr>
EOD;

// Fetch sales data
$stmt = mysqli_prepare($conn, "
    SELECT p.*, s.name AS supplier_name
    FROM purchases p
    JOIN suppliers s ON
    p.supplier_id = s.id 
    ORDER BY p.created_at DESC
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$alternate = false;
while ($row = mysqli_fetch_assoc($result)) {
    $bgColor = $alternate ? '#f2f2f2' : '#ffffff';
    $html .= "
    <tr style='background-color:{$bgColor};'>
           <td>" . date("d-m-Y", strtotime($row['created_at'])) . "</td>
                <td>" . $row['supplier_name']  . "</td>
                <td>" . $row['item_name']  . "</td>
                <td>" . $row['quantity']  . "</td>
                <td>" .  number_format($row['rate'], 2)  . "</td>
                <td>" .  number_format($row['total'], 2)  . "</td>
                <td>" .  number_format($row['paid'], 2)  . "</td>
                <td>" .  ucfirst($row['remaining'])  . "</td>
    </tr>";
    $alternate = !$alternate;
}

$html .= "</table>";

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("sales_report.pdf", "I");
