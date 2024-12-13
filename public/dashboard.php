<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
    background-image: url('../images/cap.jpg'); /* Background image for pharmacy theme */
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    font-family: 'Verdana', sans-serif;
    color: #fff;
    margin: 0;
    padding: 0;
}
        </style>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/pre loader.js"></script>
</head>
<body onload="myFunction()" style="margin:0;">

      
      <div id="loader">
        <div class="loading-text">
          
          <span>W</span>
          <span>E</span>
          <span>L</span>
          <span>C</span>
          <span>O</span>
          <span>M</span>
          <span>E</span>
        
        </div>
      </div>
      
      
     <div style="display:none;" id="myDiv" class="animate-bottom">
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
    
    
        <!-- Midflex -->
        <div class="d2">
            <p class="center">MediCare Lanka Pharmacy</p>
            
        </div>

        <div class="d10">

          <div class="d11" >
            <div class="container2">
                <img src="../images/customer.png"  class="image2">
                <div class="overlay">
                  <div class="text2"><a href="manage_customer.php">Manage Customers</a></div>
                </div>
            </div>
          </div>

          <div class="d12" >
            <div class="container2">
                <img src="../images/medicine.png"  class="image2">
                <div class="overlay1">
                  <div class="text2"><a href="manage_medicine.php">Manage Medicines</a></div>
                </div>
            </div>
          </div>

          <div class="d13" >
            <div class="container2">
                <img src="../images/supplier.png"  class="image2">
                <div class="overlay2">
                  <div class="text2"><a href="manage_supplier.php">Manage Suppliers</a></div>
                </div>
            </div>
          </div>

          <div class="d11" >
            <div class="container2">
                <img src="../images/invoice.png"  class="image2">
                <div class="overlay3">
                  <div class="text2"><a href="manage_invoice.php">Manage Invoices</a></div>
                </div>
            </div>
          </div>

          <div class="d12" >
            <div class="container2">
                <img src="../images/sales.png"  class="image2">
                <div class="overlay">
                  <div class="text2"><a href="sales_report.php">Sales Report</a></div>
                </div>
            </div>
          </div>

          <div class="d13" >
            <div class="container2">
                <img src="../images/purchase.png"  class="image2">
                <div class="overlay2">
                  <div class="text2"><a href="purchase_report.php">Purchase Report</a></div>
                </div>
            </div>
          </div>
          
        </div>
    
</body>
</html>
