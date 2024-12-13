<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Check if the invoice ID is provided via URL parameter
if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];

    // Fetch invoice details from the invoices table
    $invoice_sql = "SELECT invoices.invoice_id, customers.name AS customer_name, invoices.total_amount, invoices.date
                    FROM invoices
                    JOIN customers ON invoices.customer_id = customers.customer_id
                    WHERE invoices.invoice_id = $invoice_id";
    $invoice_result = $conn->query($invoice_sql);

    // Fetch invoice items from the invoice_items table
    $items_sql = "SELECT invoice_items.*, medicines.name AS medicine_name 
                  FROM invoice_items 
                  JOIN medicines ON invoice_items.medicine_id = medicines.medicine_id
                  WHERE invoice_items.invoice_id = $invoice_id";
    $items_result = $conn->query($items_sql);

    if ($invoice_result->num_rows > 0) {
        $invoice = $invoice_result->fetch_assoc();
    } else {
        echo "<script>alert('Invoice not found!'); window.location='manage_invoice.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invoice ID is missing!'); window.location='manage_invoice.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoice</title>
    <style>
        /* General Styling */
        body {
            background-image: url('../images/cap.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            font-family: Verdana, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

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

        .container {
            width: 60%;
            background-color: rgba(22, 22, 22, 0.8);
            border-radius: 10px;
            padding: 40px;
            margin: 100px auto;
            box-sizing: border-box;
            border: 1px solid rgba(255, 189, 47, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            color: #ffc107;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid rgba(255, 189, 47, 0.6);
            text-align: center;
        }

        .table th {
            background-color: #505850;
            color: #fff;
        }

        .table td {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .table td:hover {
            background-color: rgba(255, 255, 47, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            background-color: rgb(255, 189, 47);
            color: rgb(14, 14, 14);
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            transition: 0.3s ease;
        }

        .btn:hover {
            background-color: rgb(255, 255, 255);
            color: rgb(14, 14, 14);
            font-size: 18px;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            h2, h3 {
                font-size: 24px;
            }

            .table th, .table td {
                font-size: 14px;
                padding: 8px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
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
        <h2>Invoice Details</h2>

        <h3>Invoice Information</h3>
        <p><strong>Invoice ID:</strong> <?php echo $invoice['invoice_id']; ?></p>
        <p><strong>Customer:</strong> <?php echo $invoice['customer_name']; ?></p>
        <p><strong>Total Amount:</strong> <?php echo number_format($invoice['total_amount'], 2); ?></p>
        <p><strong>Date:</strong> <?php echo $invoice['date']; ?></p>

        <h3>Invoice Items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($items_result->num_rows > 0) {
                    while ($item = $items_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $item['medicine_name'] . "</td>";
                        echo "<td>" . $item['quantity'] . "</td>";
                        echo "<td>" . number_format($item['price'], 2) . "</td>";
                        echo "<td>" . number_format($item['amount'], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No items found for this invoice.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="manage_invoice.php" class="btn">Back to Invoices</a>
    </div>
</body>
</html>
