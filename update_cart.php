
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productID = (int) ($_POST['product_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($productID > 0) {

        $stmt = $conn->prepare(
            "SELECT CartItemID, Quantity
             FROM cartitems WHERE ProductID = ?"
        );

        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if ($action === 'add') {

            if ($item) {
                $stmt = $conn->prepare(
                    "UPDATE cartitems
                     SET Quantity = Quantity + 1
                     WHERE ProductID = ?"
                );
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO cartitems
                     (ProductID, Quantity) VALUES (?, 1)"
                );
            }

            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $stmt->close();

        } elseif ($action === 'decrease' && $item) {

            if ($item['Quantity'] > 1) {
                $stmt = $conn->prepare(
                    "UPDATE cartitems
                     SET Quantity = Quantity - 1
                     WHERE ProductID = ?"
                );
            } else {
                $stmt = $conn->prepare(
                    "DELETE FROM cartitems
                     WHERE ProductID = ?"
                );
            }

            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $stmt->close();

        } elseif ($action === 'remove') {

            $stmt = $conn->prepare(
                "DELETE FROM cartitems
                 WHERE ProductID = ?"
            );

            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header("Location: index.php");
exit;
