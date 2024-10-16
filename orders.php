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

// Query to get the orders for the logged-in user, including order_date
$sql = "SELECT product_name, quantity, total_price, payment_mode, customer_name, status, order_date
        FROM orders 
        WHERE customer_id = '$user_id'";

$result = $conn->query($sql);

if (!$result) {
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
        background-color: #343a40;
        color: white;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #e9ecef;
    }
    tr:hover {
        cursor: pointer;
        background-color: #6c757d;
        color: white;
        transition: background-color 0.3s ease;
    }
    .no-orders {
        text-align: center;
        font-size: 1.5em;
        margin: 20px;
        color: #6c757d;
    }
    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease-in-out;
    }
    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 60%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: fadeIn 0.5s;
    }
    .modal-header {
        font-size: 1.8em;
        margin-bottom: 10px;
        color: #343a40;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 1.5em;
        color: #333;
        cursor: pointer;
        transition: color 0.3s;
    }
    .close:hover {
        color: #ff0000;
    }
    #modalContent {
        font-size: 1.2em;
        color: #555;
        line-height: 1.6;
    }

    /* Animation for modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>";

if ($result->num_rows > 0) {
    // Start outputting the table
    echo "<table id='ordersTable'>";
    echo "<tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Customer Name</th>
            <th>Status</th>
            <th>Order Date</th>
          </tr>";

    $orders = array();
    
    while ($row = $result->fetch_assoc()) {
        // Calculate dispatched date as 2 days after order_date
        $order_date = $row['order_date'];
        $dispatched_date = date('Y-m-d H:i:s', strtotime($order_date . ' + 2 days noon'));
        
        // Calculate delivery date as 5 days after dispatched date
        $delivery_date = date('Y-m-d H:i:s', strtotime($dispatched_date . ' + 5 days noon'));

        // Store order details in an array for JavaScript
        $orders[] = array(
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'total_price' => $row['total_price'],
            'payment_mode' => $row['payment_mode'],
            'customer_name' => $row['customer_name'],
            'status' => $row['status'],
            'order_date' => $order_date,
            'dispatched_date' => $dispatched_date,
            'delivery_date' => $delivery_date
        );

        echo "<tr onclick='showOrderDetails(" . (count($orders) - 1) . ")'>
                <td>" . htmlspecialchars($row['product_name']) . "</td>
                <td>" . htmlspecialchars($row['quantity']) . "</td>
                <td>" . htmlspecialchars($row['total_price']) . "</td>
                <td>" . htmlspecialchars($row['payment_mode']) . "</td>
                <td>" . htmlspecialchars($row['customer_name']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td>" . htmlspecialchars($row['order_date']) . "</td>
              </tr>";
    }

    echo "</table>";

    // Convert the orders array to JSON for JavaScript
    $orders_json = json_encode($orders);
} else {
    echo "<div class='no-orders'>No orders found.</div>";
}

$conn->close();
?>

<!-- Add modal HTML and JavaScript -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalContent">
            <!-- Dynamic order details will go here -->
        </div>
    </div>
</div>

<script>
    var orders = <?php echo $orders_json; ?>;

    // Function to show order details in a modal
    function showOrderDetails(orderIndex) {
        var order = orders[orderIndex];

        var modalContent = "<div class='modal-header'>View Details</div>" +
                           
                           "<strong>Order Date:</strong> " + order.order_date + "<br>" +
                           "<strong>Dispatched Date:</strong> " + order.dispatched_date + "<br>" +
                           "<strong>Delivery Date:</strong> " + order.delivery_date;

        document.getElementById("modalContent").innerHTML = modalContent;
        document.getElementById("orderModal").style.display = "flex"; // Show the modal
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById("orderModal").style.display = "none";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById("orderModal")) {
            closeModal();
        }
    }
</script>
