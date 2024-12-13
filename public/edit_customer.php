<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Check if the customer ID is provided via URL parameter
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Fetch the customer details from the database
    $sql = "SELECT * FROM customers WHERE customer_id = $customer_id";
    $result = $conn->query($sql);

    // Check if customer exists
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        echo "<script>alert('Customer not found!'); window.location='manage_customer.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Customer ID is missing!'); window.location='manage_customer.php';</script>";
    exit();
}

// Handle form submission to update customer details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Update customer data in the database
    $update_sql = "UPDATE customers SET name = '$name', contact = '$contact', email = '$email' WHERE customer_id = $customer_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Customer details updated successfully!'); window.location='manage_customer.php';</script>";
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
    <title>Edit Customer</title>
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

        input[type="text"], input[type="email"] {
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

            input[type="text"], input[type="email"] {
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
        <h2>Edit Customer</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Customer Name:</label>
                <input type="text" name="name" id="name" required placeholder="Enter customer name" value="<?php echo $customer['name']; ?>">
            </div>
            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" name="contact" id="contact" required placeholder="Enter contact number" value="<?php echo $customer['contact']; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required placeholder="Enter email address" value="<?php echo $customer['email']; ?>">
            </div>
            <button type="submit">Update Customer</button>
        </form>
    </div>
</body>
</html>
