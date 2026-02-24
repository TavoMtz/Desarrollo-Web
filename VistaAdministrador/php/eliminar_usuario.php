<?php

include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idUsuario'])) {
    $idUsuario = (int)$_POST['idUsuario'];

    // Delete user
    $query = "DELETE FROM ca_usuarios WHERE id = $idUsuario";
    if (mysqli_query($link, $query)) {
        header("Location: manage_users.php?success=eliminado");
        exit();
    } else {
        header("Location: manage_users.php?error=eliminar");
        exit();
    }
} else {
    header("Location: manage_users.php?error=solicitud_invalida");
    exit();
}
mysqli_close($link);
?>