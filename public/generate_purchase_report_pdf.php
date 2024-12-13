<?php
// Include the TCPDF library
require_once('../tcpdf/tcpdf.php');

// Start session to check if the user is logged in (optional)
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Get the date range from the URL
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Fetch total purchases in the selected date range
$total_purchase_sql = "SELECT SUM(amount) AS total_purchase FROM purchases WHERE date BETWEEN '$start_date' AND '$end_date'";
$total_purchase_result = $conn->query($total_purchase_sql);
$total_purchase = $total_purchase_result->fetch_assoc()['total_purchase'];

// Fetch purchases grouped by supplier
$purchases_by_supplier_sql = "SELECT suppliers.name AS supplier_name, SUM(purchases.amount) AS total_amount 
                               FROM purchases 
                               JOIN suppliers ON purchases.supplier_id = suppliers.supplier_id
                               WHERE purchases.date BETWEEN '$start_date' AND '$end_date'
                               GROUP BY suppliers.name";
$purchases_by_supplier_result = $conn->query($purchases_by_supplier_sql);

// Fetch detailed purchase information
$purchase_details_sql = "SELECT purchases.purchase_id, suppliers.name AS supplier_name, purchases.amount, purchases.date 
                         FROM purchases 
                         JOIN suppliers ON purchases.supplier_id = suppliers.supplier_id
                         WHERE purchases.date BETWEEN '$start_date' AND '$end_date'";
$purchase_details_result = $conn->query($purchase_details_sql);

// Create a new TCPDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Purchase Report');
$pdf->SetSubject('Purchase Report');

// Add a page
$pdf->AddPage();

// Set header for the PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Purchase Report', 0, 1, 'C');

// Display total purchase
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Total Purchase Amount: LKR. ' . number_format($total_purchase, 2), 0, 1);

// Purchases by supplier
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Purchases by Supplier', 0, 1);

$pdf->SetFont('helvetica', '', 12);
if ($purchases_by_supplier_result->num_rows > 0) {
    while ($row = $purchases_by_supplier_result->fetch_assoc()) {
        $pdf->Cell(0, 10, $row['supplier_name'] . ': LKR. ' . number_format($row['total_amount'], 2), 0, 1);
    }
} else {
    $pdf->Cell(0, 10, 'No purchase data found for this period.', 0, 1);
}

// Detailed purchase information
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Detailed Purchase Information', 0, 1);

$pdf->SetFont('helvetica', '', 12);
if ($purchase_details_result->num_rows > 0) {
    while ($row = $purchase_details_result->fetch_assoc()) {
        $pdf->MultiCell(0, 10, 'Purchase ID: ' . $row['purchase_id'] . 
                             ', Supplier: ' . $row['supplier_name'] . 
                             ', Amount: LKR. ' . number_format($row['amount'], 2) . 
                             ', Date: ' . $row['date'], 0, 1);
    }
} else {
    $pdf->Cell(0, 10, 'No detailed purchase data found for this period.', 0, 1);
}

// Output the PDF
$pdf->Output('Purchase_Report.pdf', 'D');

?>
