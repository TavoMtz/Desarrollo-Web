<?php
header('Content-Type: application/json');
include 'conect.php';
$link = Conectarse();

if (!$link) {
    $response = array('status' => 'error', 'message' => 'Error de conexión a la base de datos: ' . mysqli_connect_error());
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreTitular = $_POST['nombre'] ?? null;
    $numeroTarjeta = $_POST['tarjeta'] ?? null;
    $fechaVencimiento = $_POST['fecha'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
    $carritoJSON = $_POST['carrito'] ?? null;
    $total = $_POST['total'] ?? 0;

    if ($carritoJSON) {
        $carrito = json_decode($carritoJSON, true);
        $articulosVendidos = count($carrito);

        mysqli_begin_transaction($link);
        $error = false;
        $ventaId = null;

        $stmtVenta = mysqli_prepare($link, "INSERT INTO Ventas (Articulos_vendidos, Total, Fecha) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($stmtVenta, "id", $articulosVendidos, $total);

        if (mysqli_stmt_execute($stmtVenta)) {
            $ventaId = mysqli_insert_id($link);

            foreach ($carrito as $item) {
                $productoId = $item['id'] ?? null;
                $precioUnitario = $item['precio'] ?? 0;
                $cantidad = 1; // Asumiendo 1 unidad por ítem

                $stmtInsumoPedido = mysqli_prepare($link, "INSERT INTO Insumos_pedidos (Precio_Unitario, Nombre_Insumo, Cantidad) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmtInsumoPedido, "dsi", $precioUnitario, $item['nombre'], $cantidad);

                if (mysqli_stmt_execute($stmtInsumoPedido)) {
                    $insumoPedidoId = mysqli_insert_id($link);

                    $stmtRelacion = mysqli_prepare($link, "INSERT INTO Insumos_pedidos_has_Productos (Insumos_pedidos_ID, Productos_ID) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmtRelacion, "ii", $insumoPedidoId, $productoId);

                    if (!mysqli_stmt_execute($stmtRelacion)) {
                        $error = true;
                        $response = array('status' => 'error', 'message' => 'Error al relacionar insumo con producto: ' . mysqli_error($link));
                        break;
                    }
                    mysqli_stmt_close($stmtRelacion);
                } else {
                    $error = true;
                    $response = array('status' => 'error', 'message' => 'Error al insertar en Insumos_pedidos: ' . mysqli_error($link));
                    break;
                }
                mysqli_stmt_close($stmtInsumoPedido);
            }

            if (!$error) {
                mysqli_commit($link);
                $response = array('status' => 'success', 'message' => 'Pedido registrado en Ventas con éxito.', 'venta_id' => $ventaId);
            } else {
                mysqli_rollback($link);
            }

        } else {
            $response = array('status' => 'error', 'message' => 'Error al registrar la venta: ' . mysqli_error($link));
        }

        mysqli_close($link);
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Método no permitido.');
    }
    echo json_encode($response);
}
?>