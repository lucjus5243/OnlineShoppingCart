
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM cartitems");
}

header("Location: index.php");
exit;
