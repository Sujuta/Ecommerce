<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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
            padding: 50px;
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
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            width: 90%;
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

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }

        .register-link a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #555;
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


    <form action="login.php" method="POST">
        <h2>Login</h2>
        
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit" name="login" >Login</button>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>.
        </div>
    </form>
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                You have logged in successfully!
            </div>
            <div class="modal-footer" >
            <button type="button" class="btn custom-gray-button" data-bs-dismiss="modal" onclick="redirectToHome()">Go to Home page</button>
            </div>
        </div>
    </div>
</div>


    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        // Get form data
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user from database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $db_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $db_password)) {
                session_start();
                $_SESSION['username'] = $db_username;
                $_SESSION['user_id'] = $id;
                $loggedIn = true;
                
                // Store logged in status in localStorage
                echo "<script>
                        localStorage.setItem('loggedIn', '".json_encode($loggedIn)."');
                        // Trigger Bootstrap modal
                        var myModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
                        myModal.show();
                      </script>";
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
            
        } else {
            echo "<script>alert('No user found with that username.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <script>
    function redirectToHome() {
        window.location.href = 'onlinepayement.php';
    }
</script>

</body>
</html>
