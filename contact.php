<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submitMessage'])) {
    $name = $_POST['name'];
    $email = $_POST['from'];
    $message = $_POST['message'];

    // SQL query to insert the data into the database
    $sql = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Message sent successfully!');
               
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
              </script>";
    }

    $conn->close();
}
?>
