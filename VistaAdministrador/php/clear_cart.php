<?php
session_start();

// Clear the cart data from the session
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Return a success response
http_response_code(200);
echo json_encode(['status' => 'success']);
?>