<?php
session_start();

// Assuming you have already connected to the database using `$conn`
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];
    $customer_id = $_SESSION['user_id'];  // Assuming you are using session for user login
    $customer_name = $_POST['customer_name']; // Retrieve customer name from form or session
    $product_name = $_POST['product_name'];   // Product details
    $qty = $_POST['qty'];
    $total_price = $_POST['total_price'];
    $payment_mode = $_POST['payment_mode'];  // Razorpay or Cash on Delivery

    // Insert the order details into the orders table
    $sql = "INSERT INTO orders1 (customer_id, customer_name, product_name, qty, total_price, payment_mode)
            VALUES ('$customer_id', '$customer_name', '$product_name', '$qty', '$total_price', '$payment_mode')";

    if ($conn->query($sql) === TRUE) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
