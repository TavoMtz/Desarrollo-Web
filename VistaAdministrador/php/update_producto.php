<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProducto = mysqli_real_escape_string($link, $_POST['idProducto']);
    $nombreProducto = mysqli_real_escape_string($link, $_POST['Nombre']);
    $precio = mysqli_real_escape_string($link, $_POST['Precio']);

    // Handle file upload
    $imagenPath = null;
    if (isset($_FILES["Imagen"]) && $_FILES["Imagen"]["error"] == 0) {
        $target_dir = __DIR__ . "/../resources/img/productos/"; // Corrected path with directory separator
        $imagen = strtolower(str_replace(' ', '_', $nombreProducto)) . "_" . basename($_FILES["Imagen"]["name"]); // Rename file with Nombre input
        $target_file = $target_dir . $imagen;

        // Validate the image
        $check = getimagesize($_FILES["Imagen"]["tmp_name"]);
        if ($check !== false) {
            // Ensure the target directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
            }

            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES["Imagen"]["tmp_name"], $target_file)) {
                $imagenPath = "resources/img/productos/" . $imagen; // Save the relative path to the database
            } else {
                echo "Error al subir la imagen.";
                exit();
            }
        } else {
            echo "El archivo no es una imagen válida.";
            exit();
        }
    }

    // Update the product in the database (without updating Categoria)
    $query = "UPDATE ca_productos SET 
              Nombre = '$nombreProducto', 
              Precio = '$precio'"; // Removed Categoria from the query

    if ($imagenPath) {
        $query .= ", Imagen = '$imagenPath'";
    }

    $query .= " WHERE Id = '$idProducto'";

    if (mysqli_query($link, $query)) {
        header("Location: ../html/producto_editado.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>