<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if ($data && count($data) > 0) {
    $_SESSION['cart'] = $data; // Store the cart in the session
    echo json_encode(['status' => 'success']);
} else {
    unset($_SESSION['cart']); // Clear the cart from the session if it's empty
    echo json_encode(['status' => 'success', 'message' => 'Cart cleared']);
}
?>