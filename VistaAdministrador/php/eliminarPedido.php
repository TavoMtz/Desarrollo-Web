<?php
include("conex.php");
$link = Conectarse();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pedido = mysqli_real_escape_string($link, $_GET['id']);

    $query = "DELETE FROM ca_pedidos WHERE id = $id_pedido";

    if (mysqli_query($link, $query)) {
        // Redirigir de vuelta a la página de administración con un mensaje de éxito
        header("Location: pedidos.php?mensaje=pedido_eliminado");
        exit();
    } else {
        // Redirigir de vuelta a la página de administración con un mensaje de error
        header("Location: pedidos.php?error=error_al_eliminar");
        exit();
    }
} else {
    // Si no se proporciona un ID válido, redirigir con un mensaje de error
    header("Location: pedidos.php?error=id_pedido_invalido");
    exit();
}

mysqli_close($link);
?>