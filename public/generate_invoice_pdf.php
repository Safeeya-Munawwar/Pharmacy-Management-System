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

// Check if invoice ID is provided
if (!isset($_GET['invoice_id'])) {
    echo "Invoice ID is required.";
    exit();
}

$invoice_id = intval($_GET['invoice_id']);

// Fetch invoice details
$invoice_sql = "SELECT invoices.invoice_id, customers.name AS customer_name, invoices.total_amount, invoices.date
                FROM invoices
                JOIN customers ON invoices.customer_id = customers.customer_id
                WHERE invoices.invoice_id = $invoice_id";
$invoice_result = $conn->query($invoice_sql);

if ($invoice_result->num_rows === 0) {
    echo "Invoice not found.";
    exit();
}
$invoice = $invoice_result->fetch_assoc();

// Fetch invoice items
$items_sql = "SELECT invoice_items.*, medicines.name AS medicine_name
              FROM invoice_items
              JOIN medicines ON invoice_items.medicine_id = medicines.medicine_id
              WHERE invoice_items.invoice_id = $invoice_id";
$items_result = $conn->query($items_sql);

// Create a new TCPDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Pharmacy');
$pdf->SetTitle('Invoice ' . $invoice['invoice_id']);
$pdf->SetSubject('Invoice Details');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add title and invoice information
$pdf->Cell(0, 10, 'Invoice Details', 0, 1, 'C');
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Invoice ID: ' . $invoice['invoice_id'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Customer Name: ' . $invoice['customer_name'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Total Amount: LKR. ' . number_format($invoice['total_amount'], 2), 0, 1, 'L');
$pdf->Cell(0, 10, 'Date: ' . $invoice['date'], 0, 1, 'L');
$pdf->Ln(10);

// Add invoice items table
$pdf->Cell(80, 10, 'Medicine', 1, 0, 'C');
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
$pdf->Cell(40, 10, 'Price', 1, 0, 'C');
$pdf->Cell(40, 10, 'Amount', 1, 1, 'C');

while ($item = $items_result->fetch_assoc()) {
    $pdf->Cell(80, 10, $item['medicine_name'], 1, 0, 'L');
    $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, 'LKR. ' . number_format($item['price'], 2), 1, 0, 'C');
    $pdf->Cell(40, 10, 'LKR. ' . number_format($item['amount'], 2), 1, 1, 'C');
}

// Output the PDF
$pdf->Output('invoice_' . $invoice_id . '.pdf', 'I'); // 'I' to display in the browser
?>
