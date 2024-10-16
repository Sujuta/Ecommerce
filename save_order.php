<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Assuming user is logged in
//$product_name = $_POST['product_name'];
//$quantity = $_POST['quantity'];
$products = json_decode($_POST['products'], true);
$total_price = $_POST['total_price'];
$payment_id = $_POST['payment_id'];
$status = $_POST['status'];
$Username = $_POST['Username'];

if ($products && is_array($products)) {
    foreach ($products as $product) {
        $product_name = $conn->real_escape_string($product['name']);
        $quantity = intval($product['quantity']);
        $price = floatval($product['price']);
    // Insert each product in the order
    $sql = "INSERT INTO orders (product_name, customer_id, quantity, total_price, payment_mode, customer_name, status) 
    VALUES ('$product_name', '$user_id', '$quantity', '$price', '$payment_id', '$Username', '$status')";

if (!$conn->query($sql)) {
echo "Error: " . $conn->error;
$conn->close();
exit();
}
}

echo "Order placed successfully.";
}



// Insert order details into the database
// $sql = "INSERT INTO orders (product_name, customer_id, quantity, total_price, payment_mode,customer_name ,status) 
//         VALUES ('$product_name', '$user_id', '$quantity', '$total_price', '$payment_id', '$Username','$status' )";

// if ($conn->query($sql) === TRUE) {
//     echo "Order placed successfully.";
// } else {
//     echo "Error: " . $conn->error;
// }

$conn->close();
?>
