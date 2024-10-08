<?php
include('config.php');
$product_id = $_GET['id'];
$query = "SELECT * FROM products WHERE id=$product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
?>
<h1><?php echo $product['name']; ?></h1>
<p><?php echo $product['description']; ?></p>
<p>Price: $<?php echo $product['price']; ?></p>
