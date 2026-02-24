<?php
session_start(); // Iniciar sesión

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    if ($nombre === "") {
        header("Location: formsInicioSesion.html?error=nombre_vacio");
        exit();
    } elseif ($contraseña === "") {
        header("Location: formsInicioSesion.html?error=contraseña_vacia");
        exit();
    } else {
        include("conect.php");
        $link = conectarse();

        // Escapar entradas (recomendado)
        $nombre = mysqli_real_escape_string($link, $nombre);
        $contraseña = mysqli_real_escape_string($link, $contraseña);
        $hash_password = hash('sha256', $contraseña);

        // Consulta completa
        $result = mysqli_query($link, "SELECT nombre, susuario FROM ca_usuarios WHERE nombre='$nombre' AND pass='$hash_password'");

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            // GUARDAR INFO EN LA SESIÓN
            $_SESSION["usuario"] = $row['nombre'];
            $_SESSION["tipo"] = $row['susuario'];
            $_SESSION["login_success"] = true; 

            if($row["susuario"]=="1"){
                header ("Location: ../../VistaEmpleado/php/emp_control_centre.php");
            }else if($row["susuario"]=="2"){
                header ("Location: ../../VistaAdministrador/php/admin_control_centre.php");
            }else {
                header ("Location: ../../index.php");
            }
            exit();
        } else {
            // Si los datos son incorrectos
            
            header("Location: formsInicioSesion.html?error=datos_invalidos");
            exit();
        }
    }
}
?>
