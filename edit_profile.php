<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        .profile-container {
            background-color: #fff;
            max-width: 600px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus {
            border-color: #555;
            outline: none;
        }

        button {
            display: inline-block;
            text-align: center;
            background-color: gray;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            width: 100%;
            animation: fadeIn 1.5s ease-in-out;
        }

        button:hover {
            background-color: black;
            transform: translateY(-2px);
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
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>

        <form action="update_profile.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
            <label for="pincode">Pincode:</label>
            <input type="text" name="pincode" value="<?php echo htmlspecialchars($row['pincode']); ?>" required>
            <button type="submit">Update Profile</button>
        </form>

        <?php
        } else {
            echo "<p style='color: red; text-align: center;'>User not found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
