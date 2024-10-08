<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: user-login.html');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'users_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$query = "UPDATE users SET firstname = ?, lastname = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $firstname, $lastname, $user_id);

if ($stmt->execute()) {
    header('Location: my-account.php?success=1');
} else {
    echo "Error updating record: " . $conn->error;
}
?>
