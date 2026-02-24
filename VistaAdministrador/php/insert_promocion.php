<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombrePromocion = mysqli_real_escape_string($link, $_POST['nombrePromocion']);
    $idProducto1 = mysqli_real_escape_string($link, $_POST['idProducto1']);
    $idProducto2 = mysqli_real_escape_string($link, $_POST['idProducto2']);
    $idProducto3 = mysqli_real_escape_string($link, $_POST['idProducto3']);
    $idProducto4 = mysqli_real_escape_string($link, $_POST['idProducto4']);
    $precio = mysqli_real_escape_string($link, $_POST['precio']);

    // Handle file upload
    $target_dir = __DIR__ . "/../resources/img/promociones/"; // Corrected path with directory separator
    $imagen = strtolower(str_replace(' ', '_', $nombrePromocion)) . "_" . basename($_FILES["imagen"]["name"]); // Rename file with Nombre input
    $target_file = $target_dir . $imagen;
    $uploadOk = 1;

    // Check if the file is an image
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Lo sentimos, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["imagen"]["size"] > 2000000) {
        echo "Lo sentimos, el archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Lo sentimos, tu archivo no fue subido.";
    } else {
        // Ensure the target directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
        }

        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            // Insert the data into the database
            $relative_path = "resources/img/promociones/" . $imagen; // Save the relative path to the database
            $query = "INSERT INTO ca_promociones (nombre_promocion, id_producto1, id_producto2, id_producto3, id_producto4, precio, imagen) 
                      VALUES ('$nombrePromocion', '$idProducto1', '$idProducto2', 
                              " . ($idProducto3 ? "'$idProducto3'" : "NULL") . ", 
                              " . ($idProducto4 ? "'$idProducto4'" : "NULL") . ", 
                              '$precio', '$relative_path')";

            if (mysqli_query($link, $query)) {
                // Redirect to promocion_anadida.html on success
                header("Location: ../html/promocion_anadida.html");
                exit();
            } else {
                // Display an error message if the query fails
                echo "Error: " . mysqli_error($link);
            }
        } else {
            echo "Hubo un error al subir tu archivo.";
        }
    }

    // Close the database connection
    mysqli_close($link);
}
?>