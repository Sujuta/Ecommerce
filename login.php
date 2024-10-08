<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            animation: scaleIn 1s ease-in-out; /* Apply scale-in animation to form */
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
        input[type="password"] {
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

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <form action="login.php" method="POST">
        <h2>Login</h2>
        
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>

    <?php
session_start();

if (isset($_POST['login'])) {
    // Default admin credentials
    $admin_username = 'admin';
    $admin_password = 'admin123';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is the default admin
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['user_id'] = 'admin';
        header("Location: adminpage.html");
        exit();
    }

    // Database connection for other users
    $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];

            // Update last login time
            $conn->query("UPDATE users SET last_login=NOW() WHERE id=" . $row['id']);

            header("Location: profile.php");
        } else {
            echo "<p style='color: red; text-align: center;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>No user found.</p>";
    }

    $conn->close();
}
?>

</body>
</html>
