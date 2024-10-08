<?php include('config.php'); ?>
<div class="product-list">
    <?php
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<p>Price: $" . $row['price'] . "</p>";
        echo "<a href='product1-details.php?id=" . $row['id'] . "'>View Details</a>";
        echo "</div>";
    }
    ?>
</div>
