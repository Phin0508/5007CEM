<?php
session_start();
require 'src/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $user_email = $_SESSION['user_email'];

    $sql = "DELETE FROM cart WHERE id = ? AND cus_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $cart_id, $user_email);
    $stmt->execute();
    $stmt->close();
}

header('Location: cart.php');
exit();
?>