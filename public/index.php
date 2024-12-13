<?php
session_start();
include('../includes/db.php');

$savedUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$savedPassword = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;

        // Handle "Remember Me"
        if (isset($_POST['rememberMe'])) {
            setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days
            setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days
        }

        header('Location: dashboard.php');
    } else {
        echo "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-image: url('../images/login1.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
        
        .row2 input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 1px;
    cursor: pointer;
    border: 2px solid #007bff;
    border-radius: 4px; /* Rounded corners */
    transition: all 0.3s ease; /* Smooth hover and interaction transitions */
    margin-top: 10px;
    margin-bottom: 10px;
}

.row2 input[type="checkbox"]:hover {
    transform: scale(1.1); /* Slightly enlarges checkbox on hover */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Glow effect when hovered */
}

.row2 input[type="checkbox"]:checked {
    accent-color: #007bff; /* Sets checkbox color when checked */
    transform: scale(1.1); /* Keeps enlargement effect when checked */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.8); /* Stronger glow when checked */
}

    </style>
    <script src="../js/pre loader.js"></script>
</head>
<body onload="myFunction()" style="margin:0;">
    <div id="loader">
        <div class="loading-text">
            <span>L</span>
            <span>O</span>
            <span>A</span>
            <span>D</span>
            <span>I</span>
            <span>N</span>
            <span>G</span>
        </div>
    </div>

    <div style="display:none;" id="myDiv" class="animate-bottom">
        <div class="d17">
            <div class="d26">
                <p class="center">System Login</p>
            </div>

            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

            <form name="form" method="POST" action="">
                <div class="row2">
                    <div class="col-25">
                        <label for="fname">User<span style="color: rgba(189, 62, 62, 0.014);">_</span>Name</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="uname" name="username" value="<?php echo htmlspecialchars($savedUsername); ?>" required>
                    </div>
                </div>

                <div class="row2">
                    <div class="col-25">
                        <label for="password">Password</label>
                    </div>
                    <div class="col-75">
                        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($savedPassword); ?>" required>
                    </div>
                </div>

                <div class="row2">
    <input type="checkbox" id="rememberMe" name="rememberMe">
    <label for="rememberMe">Remember Me</label>
</div>


                <br>
                <div class="row2">
                    <input type="submit" value="LOGIN">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
