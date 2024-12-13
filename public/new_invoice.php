<?php
// Start session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include('../includes/db.php');

// Fetch customers to display in the select box
$customer_sql = "SELECT * FROM customers";
$customers_result = $conn->query($customer_sql);

// Fetch medicines to display in the select box
$medicines_sql = "SELECT * FROM medicines";
$medicines_result = $conn->query($medicines_sql);

// Handle form submission to create new invoice
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $total_amount = 0;
    
    // Insert the invoice into the invoices table
    $invoice_sql = "INSERT INTO invoices (customer_id, total_amount, date) VALUES ('$customer_id', '$total_amount', NOW())";
    if ($conn->query($invoice_sql) === TRUE) {
        $invoice_id = $conn->insert_id; // Get the inserted invoice ID
        
        // Add the medicines and their quantities to the invoice_items table
        foreach ($_POST['medicine_id'] as $index => $medicine_id) {
            $quantity = $_POST['quantity'][$index];
            $price = $_POST['price'][$index];
            $amount = $quantity * $price;
            $total_amount += $amount; // Calculate total price
            
            // Insert each medicine item into the invoice_items table
            $invoice_items_sql = "INSERT INTO invoice_items (invoice_id, medicine_id, quantity, price, amount) 
                                  VALUES ('$invoice_id', '$medicine_id', '$quantity', '$price', '$amount')";
            $conn->query($invoice_items_sql);
        }
        
        // Update the total amount in the invoices table
        $update_invoice_sql = "UPDATE invoices SET total_amount = '$total_amount' WHERE invoice_id = '$invoice_id'";
        $conn->query($update_invoice_sql);
        
        echo "<script>alert('Invoice created successfully!'); window.location='manage_invoice.php';</script>";
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
    <title>Create New Invoice</title>
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
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
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

        h2, h3 {
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
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
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
        .btn-secondary {
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
        .btn:hover {
            background-color: rgb(255, 189, 47);
            color: rgb(14, 14, 14);
            font-size: 22px;
        }
        hr {
            border: 0;
            height: 1px;
            background: #ccc;
            margin: 20px 0;
        }
    </style>
    <script>
        // Function to calculate total price based on quantity and price
        function updateTotalPrice(index) {
            var quantity = document.getElementsByName('quantity[]')[index].value;
            var price = document.getElementsByName('price[]')[index].value;
            var totalPriceField = document.getElementsByName('total_price[]')[index];
            
            // Calculate total price
            if (quantity && price) {
                var totalPrice = quantity * price;
                totalPriceField.value = totalPrice.toFixed(2);
            } else {
                totalPriceField.value = '0.00';
            }
        }
    </script>
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
        <h2>Create New Invoice</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <select name="customer_id" id="customer_id" required class="form-control">
                    <option value="">Select Customer</option>
                    <?php while ($customer = $customers_result->fetch_assoc()) { ?>
                        <option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <h3>Medicines</h3>
            <div id="medicine-fields">
                <div class="medicine-item">
                    <div class="form-group">
                        <label for="medicine_id">Medicine:</label>
                        <select name="medicine_id[]" class="form-control" required>
                            <option value="">Select Medicine</option>
                            <?php while ($medicine = $medicines_result->fetch_assoc()) { ?>
                                <option value="<?php echo $medicine['medicine_id']; ?>"><?php echo $medicine['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity[]" class="form-control" required oninput="updateTotalPrice(0)">
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" step="0.01" name="price[]" class="form-control" required oninput="updateTotalPrice(0)">
                    </div>
                    <div class="form-group">
                        <label for="total_price">Total Price:</label>
                        <input type="text" name="total_price[]" class="form-control" readonly>
                    </div>
                    <hr>
                </div>
            </div>

            <button type="button" id="add-more" class="btn btn-secondary">Add More Medicines</button>
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </form>
    </div>

    <script>
        // Function to dynamically add more medicine rows
        document.getElementById('add-more').addEventListener('click', function() {
            var newMedicineItem = document.querySelector('.medicine-item').cloneNode(true);
            var medicineFields = document.getElementById('medicine-fields');
            var newIndex = medicineFields.getElementsByClassName('medicine-item').length;
            
            // Update the event listeners for the new fields
            var quantityInput = newMedicineItem.querySelector('input[name="quantity[]"]');
            var priceInput = newMedicineItem.querySelector('input[name="price[]"]');
            var totalPriceInput = newMedicineItem.querySelector('input[name="total_price[]"]');
            
            quantityInput.setAttribute('oninput', 'updateTotalPrice(' + newIndex + ')');
            priceInput.setAttribute('oninput', 'updateTotalPrice(' + newIndex + ')');
            
            // Append the new medicine item
            medicineFields.appendChild(newMedicineItem);
        });
    </script>
</body>
</html>
