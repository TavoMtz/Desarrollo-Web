<?php
//vemos si el usuario y contraseña es váildo
if ($_POST["Nombre"] == "Gustavo" && $_POST["Contraseña"] == "040603") { //usuario y contraseña válidos
    //defino una sesion y guardo datos
    session_start();
    $_SESSION["autentificado"] = "SI";
    header("Location: validarDatos.php"); //cambiar a uno php existente
} else {
    //si no existe le mando otra vez a la portada
    header("Location: ../../index.php?errorusuario=1");
}
