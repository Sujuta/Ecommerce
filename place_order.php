<?php
// Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the data sent from the checkout form
$product_name = $_POST['product_name'];
$product_description = $_POST['product_description'];
$product_price = $_POST['product_price'];
$product_quantity = $_POST['product_quantity'];
$total_price = $_POST['total_price'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$customer_address = $_POST['customer_address'];
$payment_mode = $_POST['payment_mode'];

// Insert the order details into the database
$sql = "INSERT INTO orders (product_name, product_description, product_price, product_quantity, total_price, customer_name, customer_email, customer_address, payment_mode, order_date)
        VALUES ('$product_name', '$product_description', '$product_price', '$product_quantity', '$total_price', '$customer_name', '$customer_email', '$customer_address', '$payment_mode', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
