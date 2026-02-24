<?php
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreProducto = mysqli_real_escape_string($link, $_POST['Nombre']);
    $precio = mysqli_real_escape_string($link, $_POST['Precio']);
    $categoria = mysqli_real_escape_string($link, $_POST['categoria']); // Ensure categoria is sanitized

    // Handle file upload
    $target_dir = __DIR__ . "/../resources/img/productos/"; // Corrected path with directory separator
    $imagen = strtolower(str_replace(' ', '_', $nombreProducto)) . "_" . basename($_FILES["Imagen"]["name"]); // Rename file with Nombre input
    $target_file = $target_dir . $imagen;
    $uploadOk = 1;

    // Check if the file is an image
    $check = getimagesize($_FILES["Imagen"]["tmp_name"]);
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
    if ($_FILES["Imagen"]["size"] > 2000000) {
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
        if (move_uploaded_file($_FILES["Imagen"]["tmp_name"], $target_file)) {
            // Insert data into the database
            $relative_path = "resources/img/productos/" . $imagen; // Save the relative path to the database
            $query = "INSERT INTO ca_productos (Nombre, Precio, Imagen, Categoria) 
                      VALUES ('$nombreProducto', '$precio', '$relative_path', '$categoria')"; // Enclose $categoria in quotes

            if (mysqli_query($link, $query)) {
                header("Location: ../html/producto_nuevo.html");
                exit();
            } else {
                echo "Error: " . mysqli_error($link);
            }
        } else {
            echo "Hubo un error al subir tu archivo.";
        }
    }

    mysqli_close($link);
}
?>