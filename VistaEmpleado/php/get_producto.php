<?php
include("conex.php");
$link = Conectarse();

if (isset($_GET['id'])) {
    $idProducto = mysqli_real_escape_string($link, $_GET['id']);
    $query = "SELECT Nombre, Categoria, Precio FROM ca_productos WHERE Id = '$idProducto'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        echo json_encode($product);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}

mysqli_close($link);
?>