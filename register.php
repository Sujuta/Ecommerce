<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #fff;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #555;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            width: 100%;
            background-color: gray;
            color: white;
            border: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: black;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }

        .login-link a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #555;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 0.9em;
        }

        .password-strength span {
            font-weight: bold;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        // JavaScript client-side validation
        function validateForm() {
            const phone = document.forms["registerForm"]["phone"].value;
            const pincode = document.forms["registerForm"]["pincode"].value;
            const name = document.forms["registerForm"]["name"].value;

            const nameRegex = /^[A-Za-z\s]+$/;
if (!nameRegex.test(name)) {
    alert("Name should only contain letters and spaces.");
    return false;
}


            if (isNaN(phone) || phone.length != 10) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            if (isNaN(pincode) || pincode.length != 6) {
                alert("Please enter a valid 6-digit pincode.");
                return false;
            }

            return true;
        }

        // JavaScript to restrict input for numeric fields
        function isNumberKey(evt) {
            const charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        // JavaScript for Password Strength Check
        function checkPasswordStrength() {
            const password = document.getElementById("password").value;
            const strengthText = document.getElementById("password-strength");
            let strength = "";

            if (password.length < 6) {
                strength = "Weak (Minimum 6 characters required)";
                strengthText.style.color = "red";
            } else if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/) && password.match(/[@$!%*?&#]/)) {
                strength = "Strong";
                strengthText.style.color = "green";
            } else if (password.match(/[a-zA-Z]/) && password.match(/\d/)) {
                strength = "Medium (Add special characters and mix uppercase and lowercase)";
                strengthText.style.color = "orange";
            } else {
                strength = "Weak (Add numbers, special characters, and mix uppercase and lowercase)";
                strengthText.style.color = "red";
            }

            strengthText.innerHTML = "Password Strength: <span>" + strength + "</span>";
        }
    </script>
</head>
<body>
    <form name="registerForm" action="register.php" method="POST" onsubmit="return validateForm()">
        <h2>Register</h2>
        
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" onkeyup="checkPasswordStrength()" required>
        <div class="password-strength" id="password-strength"></div>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="name">Name:</label>
        <input type="text" name="name" pattern="[A-Za-z\s]+" maxlength="50" title="Name should only contain letters and spaces." required>

        <label for="address">Address:</label>
        <input type="text" name="address">

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" maxlength="10" onkeypress="return isNumberKey(event)" required>

        <label for="pincode">Pincode:</label>
        <input type="text" name="pincode" maxlength="6" onkeypress="return isNumberKey(event)" required>

        <button type="submit" name="register">Register</button>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>.
        </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        // Server-side validation
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $pincode = trim($_POST['pincode']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');</script>";
            exit();
        }

        if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
            echo "<script>alert('Name should only contain letters and spaces.');</script>";
            exit();
        }

        if (!is_numeric($phone) || strlen($phone) != 10) {
            echo "<script>alert('Invalid phone number. It must be 10 digits.');</script>";
            exit();
        }

        if (!is_numeric($pincode) || strlen($pincode) != 6) {
            echo "<script>alert('Invalid pincode. It must be 6 digits.');</script>";
            exit();
        }

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, name, address, phone, pincode) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $hashed_password, $email, $name, $address, $phone, $pincode);

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
