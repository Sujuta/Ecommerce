<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Get the order_id from the URL
if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}

$order_id = $_GET['order_id'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get tracking details for the specific order
$sql = "SELECT product_name, current_location, estimated_delivery_time, status, last_updated 
        FROM orders 
        WHERE customer_id = ? AND order_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['user_id'], $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo "<h2>Tracking Information for " . htmlspecialchars($order['product_name']) . "</h2>";
    echo "<p><strong>Status:</strong> " . htmlspecialchars($order['status']) . "</p>";
    echo "<p><strong>Current Location:</strong> " . htmlspecialchars($order['current_location']) . "</p>";
    echo "<p><strong>Estimated Delivery Time:</strong> " . htmlspecialchars($order['estimated_delivery_time']) . "</p>";
    echo "<p><strong>Last Updated:</strong> " . htmlspecialchars($order['last_updated']) . "</p>";
} else {
    echo "<p>Order not found or no tracking information available.</p>";
}

$conn->close();
?>
