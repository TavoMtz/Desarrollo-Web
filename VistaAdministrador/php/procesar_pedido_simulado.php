<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("conex.php");
$link = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_data = file_get_contents('php://input');
    $cart = json_decode($cart_data, true);

    if ($cart && is_array($cart)) {
        mysqli_begin_transaction($link); // Iniciar una transacción

        // Obtener el próximo ID autoincremental para el no_orden
        $database_name = "proydweb_p2025";
        $get_next_id_query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$database_name' AND TABLE_NAME = 'ca_pedidos'";
        $result_id = mysqli_query($link, $get_next_id_query);

        if ($result_id && $row_id = mysqli_fetch_assoc($result_id)) {
            $no_orden = $row_id['AUTO_INCREMENT'];
        } else {
            mysqli_rollback($link);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener el número de orden.']);
            mysqli_close($link);
            exit();
        }

        // Extraer nombres de todos los productos en el carrito
        $nombres_productos = [];
        foreach ($cart as $item) {
            $nombres_productos[] = $item['name'] . ' (x' . $item['quantity'] . ')'; // Incluir cantidad en el nombre
        }
        $nombre_pedido = implode(', ', $nombres_productos);

        // Calcular el precio total del carrito
        $precio_total = 0;
        foreach ($cart as $item) {
            $precio_total += $item['price'] * $item['quantity'];
        }

        // Calcular la cantidad total de productos en el carrito
        $cantidad_total = 0;
        foreach ($cart as $item) {
            $cantidad_total += $item['quantity'];
        }

        $fecha = date('Y-m-d H:i:s');

        // Insertar el pedido con los nombres combinados, el precio total y la cantidad total
        $query = "INSERT INTO ca_pedidos (no_orden, nombre, precio, cantidad, fecha) VALUES ($no_orden, '$nombre_pedido', $precio_total, $cantidad_total, '$fecha')";
        if (mysqli_query($link, $query)) {
            mysqli_commit($link);
            echo json_encode(['status' => 'success', 'no_orden' => $no_orden, 'precio_total' => $precio_total, 'cantidad_total' => $cantidad_total]);
        } else {
            mysqli_rollback($link);
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar el pedido: ' . mysqli_error($link)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos del carrito inválidos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de petición no válido.']);
}

mysqli_close($link);
?>