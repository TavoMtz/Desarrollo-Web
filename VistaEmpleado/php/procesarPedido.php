<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtener los datos del formulario
    $nombre_producto = isset($_POST['producto']) ? trim($_POST['producto']) : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1; // Valor predeterminado de 1

    // 2. Saneamiento y validación de datos (¡Crucial para la seguridad!)
    $nombre_producto_seguro = mysqli_real_escape_string($link, $nombre_producto);
    $cantidad_seguro = mysqli_real_escape_string($link, $cantidad);

    // Validar que el nombre del producto no esté vacío y la cantidad sea positiva
    if (empty($nombre_producto_seguro)) {
        $error = "Por favor, selecciona un producto.";
        header("Location: pedidos_empleado.php?error=" . urlencode($error));
        exit();
    }

    if ($cantidad_seguro <= 0) {
        $error = "La cantidad debe ser mayor que cero.";
        header("Location: pedidos_empleado.php?error=" . urlencode($error));
        exit();
    }

    // 3. Obtener el precio del producto desde la tabla 'ca_productos'
    $query_precio = "SELECT Precio FROM ca_productos WHERE Nombre = '$nombre_producto_seguro'";
    $result_precio = mysqli_query($link, $query_precio);

    if ($result_precio && mysqli_num_rows($result_precio) > 0) {
        $row_precio = mysqli_fetch_assoc($result_precio);
        $precio_unitario = $row_precio['Precio'];

        // 4. Construir la consulta INSERT para 'ca_pedidos'
        $query_insert = "INSERT INTO `ca_pedidos` (`nombre`, `precio`, `cantidad`, `fecha`)
                         VALUES ('$nombre_producto_seguro', '$precio_unitario', '$cantidad_seguro', NOW())";

        // 5. Ejecutar la consulta INSERT
        if (mysqli_query($link, $query_insert)) {
            // Éxito al guardar el pedido
            $mensaje = "Pedido de $cantidad $nombre_producto realizado con éxito.";
            header("Location: pedidos_empleado.php?mensaje=" . urlencode($mensaje));
            exit();
        } else {
            // Error al guardar el pedido
            $error = "Error al realizar el pedido: " . mysqli_error($link);
            header("Location: pedidos_empleado.php?error=" . urlencode($error));
            exit();
        }
    } else {
        // No se encontró el producto en la tabla 'ca_productos'
        $error = "El producto seleccionado no existe en el menú.";
        header("Location: pedidos_empleado.php?error=" . urlencode($error));
        exit();
    }

    // 6. Cerrar la conexión
    mysqli_close($link);

} else {
    // Si se intenta acceder a este archivo por GET, redirigir
    header("Location: pedidos_empleado.php");
    exit();
}
?>