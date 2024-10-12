<?php
session_start();



$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update product status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $update_sql = "UPDATE orders SET status='$status' WHERE id='$order_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Order status updated successfully.</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error updating status: " . $conn->error . "</p>";
    }
}

// Fetch all orders
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Order Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .status-form {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .status-form select, .status-form button {
            padding: 8px;
        }
    </style>
</head>
<body>
    <h2>Order Details</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Customer Name</th>
                <th>Customer ID</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Mode</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { 
                while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo $row['customer_id']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['total_price']; ?></td>
                        <td><?php echo $row['payment_mode']; ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <form method="POST" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="On Process" <?php echo ($row['status'] == 'On Process') ? 'selected' : ''; ?>>On Process</option>
                                    <option value="Dispatched" <?php echo ($row['status'] == 'Dispatched') ? 'selected' : ''; ?>>Dispatched</option>
                                    <option value="Delivered" <?php echo ($row['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                        </td>
                    </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="9">No orders found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
