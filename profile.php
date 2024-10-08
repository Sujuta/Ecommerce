<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account Profile</title>
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

        p {
            font-size: 1.1em;
            color: #555;
            margin: 10px 0;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.8s ease forwards;
        }

        /* Adding delays for each paragraph animation */
        p:nth-child(2) {
            animation-delay: 0.5s;
        }
        p:nth-child(3) {
            animation-delay: 0.7s;
        }
        p:nth-child(4) {
            animation-delay: 0.9s;
        }
        p:nth-child(5) {
            animation-delay: 1.1s;
        }
        p:nth-child(6) {
            animation-delay: 1.3s;
        }
        p:nth-child(7) {
            animation-delay: 1.5s;
        }

        a.logout-button, a.edit-button {
            display: inline-block;
            text-align: center;
            background-color: gray;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 30px;
            transition: background-color 0.3s ease;
            width: 90%;
            animation: fadeIn 1.5s ease-in-out;
        }

        a.logout-button:hover, a.edit-button:hover {
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
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
   <a href="index.html"><img src="img/product/back arrow.gif" alt="" height="40px"></a>
        <h2>My Profile</h2>
        <?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        // Display success or error message
        if (isset($_SESSION['success'])) {
            echo "<p style='color: green; text-align: center;'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['error'])) {
            echo "<p style='color: red; text-align: center;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
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
            echo "<p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
            echo "<p><strong>Pincode:</strong> " . htmlspecialchars($row['pincode']) . "</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>User not found.</p>";
        }

        $conn->close();
        ?>
        <a href="edit_profile.php" class="edit-button">Edit Profile</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
