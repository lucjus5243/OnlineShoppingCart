<?php
require_once 'db.php';

$sql = "SELECT p.ProductID, p.ProductName,
        p.Description, p.Price,
        COALESCE(c.Quantity, 0) AS Quantity
        FROM products p
        LEFT JOIN (
            SELECT ProductID, SUM(Quantity) AS Quantity
            FROM cartitems
            GROUP BY ProductID
        ) c ON p.ProductID = c.ProductID
        ORDER BY p.ProductID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Shopping Cart</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>

<h1>Online Shopping Cart</h1>
<h2>Product Catalog</h2>

<a href="cart.php">View Shopping Cart</a>

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Quantity in Cart</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['ProductID'] ?></td>
        <td><?= htmlspecialchars($row['ProductName']) ?></td>
        <td><?= htmlspecialchars($row['Description'] ?? '') ?></td>
        <td>$<?= number_format($row['Price'], 2) ?></td>
        <td><?= $row['Quantity'] ?></td>
        <td>
            <form action="update_cart.php" method="post">
                <input type="hidden" name="product_id"
                       value="<?= $row['ProductID'] ?>">

                <button name="action" value="add">Add +</button>
                <button name="action" value="decrease">Decrease -</button>
                <button name="action" value="remove">Remove</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>


