<?php
require('vendor/autoload.php'); // Include the Razorpay SDK

use Razorpay\Api\Api;

$api = new Api('rzp_test_qrG6mDqWHvJW1A', 'aXr9U8B2ZnvDHAduuTLlxKsY');

// Get the amount from the request
$amount = $_POST['amount']; // Amount in paise (1 INR = 100 paise)

// Create an order
$orderData = [
    'receipt' => time(),
    'amount' => $amount, // Amount in paise
    'currency' => 'INR',
];

$order = $api->order->create($orderData); // Create an order
echo json_encode($order); // Return order details as JSON
