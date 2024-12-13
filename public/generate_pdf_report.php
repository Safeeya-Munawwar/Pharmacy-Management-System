<?php
// Include the TCPDF library
require_once('../tcpdf/tcpdf.php');

// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Get date range from the URL
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Fetch total sales
$total_sales_sql = "SELECT SUM(total_amount) AS total_sales FROM invoices WHERE date BETWEEN '$start_date' AND '$end_date'";
$total_sales_result = $conn->query($total_sales_sql);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Fetch sales breakdown by medicine
$sales_by_medicine_sql = "SELECT medicines.name AS medicine_name, SUM(invoice_items.quantity) AS total_quantity, 
                          SUM(invoice_items.amount) AS total_amount 
                          FROM invoice_items
                          JOIN medicines ON invoice_items.medicine_id = medicines.medicine_id
                          JOIN invoices ON invoice_items.invoice_id = invoices.invoice_id
                          WHERE invoices.date BETWEEN '$start_date' AND '$end_date'
                          GROUP BY medicines.name";
$sales_by_medicine_result = $conn->query($sales_by_medicine_sql);

// Create a new TCPDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Pharmacy');
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add title and date range
$pdf->Cell(0, 10, 'Sales Report', 0, 1, 'C');
$pdf->Cell(0, 10, 'Date Range: ' . $start_date . ' to ' . $end_date, 0, 1, 'C');
$pdf->Ln(10);

// Add total sales
$pdf->Cell(0, 10, 'Total Sales: LKR. ' . number_format($total_sales, 2), 0, 1, 'L');
$pdf->Ln(5);

// Add sales breakdown by medicine
$pdf->Cell(0, 10, 'Sales Breakdown by Medicine', 0, 1, 'L');
$pdf->Cell(80, 10, 'Medicine', 1, 0, 'C');
$pdf->Cell(40, 10, 'Quantity Sold', 1, 0, 'C');
$pdf->Cell(40, 10, 'Total Amount', 1, 1, 'C');

while ($row = $sales_by_medicine_result->fetch_assoc()) {
    $pdf->Cell(80, 10, $row['medicine_name'], 1, 0, 'L');
    $pdf->Cell(40, 10, $row['total_quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, 'LKR. ' . number_format($row['total_amount'], 2), 1, 1, 'C');
}

// Output the PDF
$pdf->Output('sales_report_' . $start_date . '_to_' . $end_date . '.pdf', 'I'); // 'I' for inline display
