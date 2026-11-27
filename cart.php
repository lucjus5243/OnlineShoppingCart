
<?php
require_once 'db.php';

$sql = "SELECT p.ProductID, p.ProductName, p.Price,
        SUM(c.Quantity) AS Quantity
        FROM products p
        INNER JOIN cartitems c
        ON p.ProductID = c.ProductID
        WHERE c.Quantity > 0
        GROUP BY p.ProductID, p.ProductName, p.Price";

$result = $conn->query($sql);
$subtotal = 0;
$totalItems = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>

<h1>Shopping Cart</h1>

<a href="index.php">Continue Shopping</a>

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Individual Price</th>
        <th>Product Total</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <?php
        $productTotal = $row['Price'] * $row['Quantity'];
        $subtotal += $productTotal;
        $totalItems += $row['Quantity'];
    ?>

    <tr>
        <td><?= $row['ProductID'] ?></td>
        <td><?= htmlspecialchars($row['ProductName']) ?></td>
        <td><?= $row['Quantity'] ?></td>
        <td>$<?= number_format($row['Price'], 2) ?></td>
        <td>$<?= number_format($productTotal, 2) ?></td>
    </tr>

    <?php endwhile; ?>
</table>

<?php
$tax = round($subtotal * 0.05, 2);
$shipping = round($subtotal * 0.10, 2);
$orderTotal = $subtotal + $tax + $shipping;
?>

<h2>Order Summary</h2>

<p>Total Items: <?= $totalItems ?></p>
<p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
<p>Tax (5%): $<?= number_format($tax, 2) ?></p>
<p>Shipping & Handling (10%):
    $<?= number_format($shipping, 2) ?></p>

<h3>Order Total: $<?= number_format($orderTotal, 2) ?></h3>

<form action="checkout.php" method="post">
    <button type="submit">Check Out</button>
</form>

</body>
</html>

