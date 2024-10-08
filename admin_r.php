<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .count {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <div class="count">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get total user count
            $sql_count = "SELECT COUNT(*) as total FROM users";
            $result_count = $conn->query($sql_count);
            if ($result_count) {
                $row_count = $result_count->fetch_assoc();
                echo "Total Registered Users: " . $row_count['total'];
            } else {
                echo "Error retrieving data: " . $conn->error;
            }
            ?>
        </div>

        <!-- User data table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Pincode</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch user data
                $sql = "SELECT id, username, name, email, address, phone, pincode FROM users";
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['pincode']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Error retrieving user data: " . $conn->error . "</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
