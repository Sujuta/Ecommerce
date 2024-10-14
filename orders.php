<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Query to get the orders for the logged-in user
$sql = "SELECT product_name, quantity, total_price, payment_mode, customer_name, status
        FROM orders 
        WHERE customer_id = '$user_id'";

$result = $conn->query($sql);

if (!$result) {
    // If the query fails, display the MySQL error
    die("Query failed: " . $conn->error);
}

echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border: 1px solid #dee2e6;
    }
    th {
        background-color: #343a40; /* Dark gray for the header */
        color: white;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #e9ecef; /* Light gray for even rows */
    }
    tr:hover {
        background-color: #6c757d; /* Darker gray on hover */
        color: white; /* Change text color on hover for better visibility */
        transition: background-color 0.3s ease;
    }
    .no-orders {
        text-align: center;
        font-size: 1.5em;
        margin: 20px;
        color: #6c757d;
    }
    @media (max-width: 768px) {
        table, th, td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }
        tr {
            margin-bottom: 15px; /* Spacing between blocks */
        }
        th {
            display: none; /* Hide header on smaller screens */
        }
        td {
            text-align: right;
            position: relative;
            padding-left: 50%; /* Create space for labels */
        }
        td::before {
            content: attr(data-label); /* Display labels */
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 10px;
            text-align: left;
            font-weight: bold;
        }
    }
</style>";

if ($result->num_rows > 0) {
    // Start outputting the table
    echo "<table>";
    echo "<tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Customer Name</th>
            <th>Status</th>
          </tr>";
    
    while ($row = $result->fetch_row()) {
        echo "<tr>
                <td>" . htmlspecialchars($row[0]) . "</td>
                <td>" . htmlspecialchars($row[1]) . "</td>
                <td>" . htmlspecialchars($row[2]) . "</td>
                <td>" . htmlspecialchars($row[3]) . "</td>
                <td>" . htmlspecialchars($row[4]) . "</td>
                <td>" . htmlspecialchars($row[5]) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='no-orders'>No orders found.</div>";
}

$conn->close();
?>
