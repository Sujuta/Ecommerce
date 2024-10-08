<?php include('config.php'); ?>
<h2>Admin - Add Product</h2>
<form method="POST" action="admin.php">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description"></textarea>
    <input type="number" name="price" placeholder="Product Price" required>
    <button type="submit" name="submit">Add Product</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', '$price')";
    if (mysqli_query($conn, $query)) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<h2>Existing Products</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>$" . $row['price'] . "</td>";
        echo "<td><a href='edit-product.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete-product.php?id=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>
