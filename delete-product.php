<?php
include('config.php');
$product_id = $_GET['id'];
$query = "DELETE FROM products WHERE id=$product_id";
mysqli_query($conn, $query);
header("Location: admin.php");
?>
