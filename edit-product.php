<?php
include('config.php');
$product_id = $_GET['id'];
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    $query = "UPDATE products SET name='$name', description='$description', price='$price' WHERE id=$product_id";
    mysqli_query($conn, $query);
    header("Location: admin.php");
}

$query = "SELECT * FROM products WHERE id=$product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
?>
<form method="POST">
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
    <textarea name="description"><?php echo $product['description']; ?></textarea>
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
    <button type="submit" name="submit">Update Product</button>
</form>
