<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update payment status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status'];
    
    // Update the orders table with payment status
    $sql = "UPDATE orders SET payment_status='$status' WHERE payment_id='$payment_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Payment status updated successfully.";
    } else {
        echo "Error updating payment status: " . $conn->error;
    }
    
    $conn->close();
}
?>
