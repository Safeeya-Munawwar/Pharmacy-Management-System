<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Initialize date range for the report (default to the current month)
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

// Fetch total sales within the selected date range
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            background-image: url('../images/3.jfif');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            font-family: Verdana, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 100px auto;
            padding: 20px;
            background-color: rgba(22, 22, 22, 0.8);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #ffc107;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
            color: #333;
        }

        .table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .table tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .table td {
            color: #fff;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 14px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 9999;
        }

        .navbar a {
            float: left;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            font-family: Verdana, sans-serif;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: #000;
        }

        .navbar a.active {
            background-color: #505850;
            color: white;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
            }

            h2 {
                font-size: 24px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            .table th, .table td {
                padding: 8px;
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php" class="active">Home</a>
    <a href="manage_customer.php">Manage Customers</a>
    <a href="manage_medicine.php">Manage Medicines</a>
    <a href="manage_supplier.php">Manage Suppliers</a>
    <a href="manage_invoice.php">Manage Invoices</a>
    <a href="sales_report.php">Sales Report</a>
    <a href="purchase_report.php">Purchase Report</a>
</div>

    <div class="container">
        <h2>Sales Report</h2>

        <!-- Filter by date range -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" style="margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="form-control" style="margin-bottom: 10px;">
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>

        <h3>Sales Summary</h3>
        <p><strong>Total Sales:</strong> LKR. <?php echo number_format($total_sales, 2); ?></p>

        <h3>Sales Breakdown by Medicine</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Total Quantity Sold</th>
                    <th>Total Sales Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sales_by_medicine_result->num_rows > 0) {
                    while ($row = $sales_by_medicine_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['medicine_name'] . "</td>";
                        echo "<td>" . $row['total_quantity'] . "</td>";
                        echo "<td>LKR. " . number_format($row['total_amount'], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No sales data found for this period.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="generate_pdf_report.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-secondary" style="margin-top: 20px;">Download PDF Report</a>
    </div>
</body>
</html>
