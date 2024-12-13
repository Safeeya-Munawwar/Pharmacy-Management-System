<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Handle form submission to add a new medicine
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    // Insert medicine data into the database
    $sql = "INSERT INTO medicines (name, stock, price) VALUES ('$name', '$stock', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New medicine added successfully!'); window.location='manage_medicine.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <style>
        /* General Styling */
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
            width: 40%;
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

        h2 {
            text-align: center;
            color: #ffc107;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            color: #fff;
            font-size: 16px;
            font-family: Verdana, sans-serif;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 189, 47, 0.6);
            border-radius: 10px;
            font-size: 16px;
            font-family: Verdana, sans-serif;
        }

        input:focus {
            outline: none;
            border: 1px solid rgba(255, 189, 47, 1);
            background-color: rgba(255, 255, 255, 0.1);
        }

        button[type="submit"] {
            background-color: rgb(255, 255, 255);
            color: rgb(14, 14, 14);
            padding: 15px 50px;
            border: 1px solid;
            border-radius: 10px;
            cursor: pointer;
            font-family: Verdana;
            font-size: 20px;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
            transition: 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: rgb(255, 189, 47);
            color: rgb(14, 14, 14);
            font-size: 22px;
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
                width: 90%;
            }

            h2 {
                font-size: 24px;
            }

            input[type="text"], input[type="number"] {
                font-size: 14px;
                padding: 10px;
            }

            button[type="submit"] {
                font-size: 16px;
                padding: 12px 40px;
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
        <h2>Add New Medicine</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Medicine Name:</label>
                <input type="text" name="name" id="name" required placeholder="Enter medicine name">
            </div>
            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" name="stock" id="stock" required placeholder="Enter stock quantity">
            </div>
            <div class="form-group">
                <label for="price">Price (per unit):</label>
                <input type="number" step="0.01" name="price" id="price" required placeholder="Enter medicine price">
            </div>
            <button type="submit">Add Medicine</button>
        </form>
    </div>
</body>
</html>
