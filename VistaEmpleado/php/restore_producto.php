<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'])) {
    $idProducto = intval($_POST['idProducto']);

    // Restore the product's category from the OriginalCategoria column
    $query = "UPDATE ca_productos 
              SET Categoria = OriginalCategoria, OriginalCategoria = NULL 
              WHERE Id = $idProducto";
    $result = mysqli_query($link, $query);

    if ($result) {
        header("Location: menu_empleado.php"); // Redirect back to the menu page
        exit();
    } else {
        echo "Error: " . mysqli_error($link);
    }
}

mysqli_close($link);
?>