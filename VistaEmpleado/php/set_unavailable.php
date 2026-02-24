<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'])) {
    $idProducto = intval($_POST['idProducto']);

    // Update the product's category to "No disponible" and store the original category
    $query = "UPDATE ca_productos 
              SET OriginalCategoria = Categoria, Categoria = 'No disponible' 
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