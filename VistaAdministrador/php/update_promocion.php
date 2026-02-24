<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idPromocion = mysqli_real_escape_string($link, $_POST['idPromocion']);
    $nombrePromocion = mysqli_real_escape_string($link, $_POST['nombrePromocion']);
    $idProducto1 = mysqli_real_escape_string($link, $_POST['idProducto1']);
    $idProducto2 = mysqli_real_escape_string($link, $_POST['idProducto2']);
    $idProducto3 = mysqli_real_escape_string($link, $_POST['idProducto3']);
    $idProducto4 = mysqli_real_escape_string($link, $_POST['idProducto4']);
    $precio = mysqli_real_escape_string($link, $_POST['precio']);

    // Handle file upload for the promotion image
    $imagenPath = null;
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $target_dir = "../img/promos/";
        $imagen = $_FILES["imagen"]["name"];
        $target_file = $target_dir . basename($imagen);

        // Validate the image
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false && move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $imagenPath = $target_file;
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    }

    // Update the promotion in the database
    $query = "UPDATE ca_promociones SET 
              nombre_promocion = '$nombrePromocion', 
              id_producto1 = '$idProducto1', 
              id_producto2 = '$idProducto2', 
              id_producto3 = " . ($idProducto3 ? "'$idProducto3'" : "NULL") . ", 
              id_producto4 = " . ($idProducto4 ? "'$idProducto4'" : "NULL") . ", 
              precio = '$precio'";

    if ($imagenPath) {
        $query .= ", imagen = '$imagenPath'";
    }

    $query .= " WHERE id = '$idPromocion'";

    if (mysqli_query($link, $query)) {
        header("Location: /html/promocion_editada.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>