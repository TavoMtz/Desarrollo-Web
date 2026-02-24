<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the product ID from the POST request
    $idProducto = isset($_POST['idProducto']) ? mysqli_real_escape_string($link, $_POST['idProducto']) : null;

    if ($idProducto) {
        // Delete the product from the database
        $query = "DELETE FROM ca_productos WHERE Id = '$idProducto'";
        if (mysqli_query($link, $query)) {
            // Redirect back to the menu page with a success message
            header("Location: ../html/producto_eliminado.html");
            exit();
        } else {
            echo "Error al eliminar el producto: " . mysqli_error($link);
        }
    } else {
        echo "ID del producto no recibido.";
    }
}

mysqli_close($link);
?>