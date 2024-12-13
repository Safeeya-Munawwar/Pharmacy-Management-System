<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Delete supplier logic
if (isset($_GET['delete'])) {
    $supplier_id = $_GET['delete'];

    // Delete the supplier from the database
    $sql = "DELETE FROM suppliers WHERE supplier_id = $supplier_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Supplier deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch all suppliers
$sql = "SELECT * FROM suppliers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Suppliers</title>
    <style>
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

        .btn-warning {
            background-color: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bd2130;
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
        <h2>Manage Suppliers</h2>

        <!-- Button to Add New Supplier -->
        <a href="add_supplier.php" class="btn btn-primary">Add New Supplier</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['supplier_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['contact'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>
                                <a href='edit_supplier.php?supplier_id=" . $row['supplier_id'] . "' class='btn btn-warning'>Edit</a>
                                <a href='?delete=" . $row['supplier_id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No suppliers found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
