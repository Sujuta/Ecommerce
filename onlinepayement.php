
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .payment-option:hover {
            background-color: #f0f0f0;
        }

        .payment-option img {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }

        .payment-option span {
            font-size: 18px;
        }

        .submit-button,
        .place-order-button {
            display: block;
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: #2d2e30;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover,
        .place-order-button:hover {
            background-color: gray;
        }

        .place-order-button {
            display: none;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .profile-info p {
            font-size: 18px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fafafa;
        }

        .profile-info strong {
            color: #2d2e30;
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
<div class="container">
        <a href="index.html"><img src="img/product/back arrow.gif" alt="" height="40px"></a>

        <h2>My Profile</h2>
        <?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: register.php");
            exit();
        }

        // Display success or error message
        if (isset($_SESSION['success'])) {
            echo "<p style='color: green; text-align: center;'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['error'])) {
            echo "<p style='color: red; text-align: center;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user details from the database
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customerName = htmlspecialchars($row['name']);
            $customerID = htmlspecialchars($row['id']);
            
            echo "<script>
                    localStorage.setItem('Username', '".json_encode($customerName)."');
                  </script>";
            // Display the information in the HTML
            echo "<p><strong>Customer ID:</strong> " . $customerID . "</p>";
            echo "<p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Name:</strong> " . $customerName . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
            echo "<p><strong>Pincode:</strong> " . htmlspecialchars($row['pincode']) . "</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>User not found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <div class="container">
        <h2 class="section-title">Payment Options</h2>

        <div class="payment-option" onclick="selectPayment('Razorpay')">
            <img src="img/product/pay.jfif" alt="Razorpay">
            <span>Razorpay</span>
        </div>
        <div class="payment-option" onclick="selectPayment('Cash on Delivery')">
            <span>Cash on Delivery</span>
        </div>

        <input type="hidden" id="payment-method" name="payment-method">

        <button type="button" class="submit-button" id="razorpay-button" onclick="initiateRazorpay('<?php echo $customerName; ?>', '<?php echo $customerID; ?>')">Proceed to Pay</button>
        <button type="button" class="place-order-button" id="place-order-button" onclick="confirmOrder()">Place Order</button>
    </div>

    <script>
        function selectPayment(method) {
            document.getElementById('payment-method').value = method;

            const options = document.querySelectorAll('.payment-option');
            options.forEach(option => {
                option.style.backgroundColor = "#fff";
            });

            const selectedOption = [...options].find(option => option.textContent.trim().includes(method));
            if (selectedOption) {
                selectedOption.style.backgroundColor = "#e6f7ff";
            }

            if (method === "Cash on Delivery") {
                document.getElementById('razorpay-button').style.display = 'none';
                document.getElementById('place-order-button').style.display = 'block';
            } else {
                document.getElementById('razorpay-button').style.display = 'block';
                document.getElementById('place-order-button').style.display = 'none';
            }
        }

        function initiateRazorpay() {
    const totalPrice = localStorage.getItem('totalPrice');
    const options = {
        key: "rzp_test_Lej3U3SZhEkigd",
        amount: totalPrice * 100, // Amount in paise
        currency: "INR",
        name: "Furniture",
        description: "Payment for order",
        handler: function (response) {
            alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
            
            // Insert order details into the database
            saveOrder(response.razorpay_payment_id, 'Paid');

            window.location.href = 'index.html';
            localStorage.removeItem('shoppingCart'); // Empty cart
        },
        prefill: {
            name: "Customer Name",
            contact: "1234567890"
        },
        notes: {
            address: "Customer Address"
        },
        theme: {
            color: "#007bff"
        }
    };
    
    const razorpay = new Razorpay(options);
    razorpay.open();
}
function saveOrder(paymentId, status) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "save_order.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    const Username  = localStorage.getItem('Username');
    // Assuming order details like product name, quantity, and total price are stored in localStorage or elsewhere
    //const cartProductName = localStorage.getItem('cartProductName');
    //const quantity = localStorage.getItem('cartProductQuantity');
    const totalPrice = localStorage.getItem('totalPrice');
   // const cartCount = localStorage.getItem('cartCount');
    const cartItems = JSON.parse(localStorage.getItem('shoppingCart')); 
    const productsJSON = JSON.stringify(cartItems);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Order saved successfully.");
        }
    };

    xhr.send("payment_id=" + paymentId + "&status=" + status + "&total_price=" + totalPrice+ "&Username="+Username + "&products=" + encodeURIComponent(productsJSON));
    
}
function confirmOrder() {
    alert('Order Placed with Cash on Delivery');

    // Insert order details into the database with COD
    saveOrder('COD', 'COD');
    
    window.location.href = 'index.html';
    localStorage.removeItem('shoppingCart'); // Empty cart
}

    </script>


</body>

</html>
