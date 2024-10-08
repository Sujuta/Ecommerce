<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$pincode = $_POST['pincode'];

// Update query
$sql = "UPDATE users SET username=?, email=?, name=?, address=?, phone=?, pincode=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $username, $email, $name, $address, $phone, $pincode, $user_id);

if ($stmt->execute()) {
    // Redirect to profile page with a success message
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: profile.php");
} else {
    // Handle errors
    $_SESSION['error'] = "Error updating profile: " . $stmt->error;
    header("Location: edit_profile.php");
}

$stmt->close();
$conn->close();
?>
