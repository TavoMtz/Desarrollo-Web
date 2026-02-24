<?php
function Conectarse()
{
    $servidor = "localhost";
    $usuario = "proydweb_p2025";
    $password = "Dw3bp202%";
    $bd = "proydweb_p2025";

    $link = mysqli_connect($servidor, $usuario, $password, $bd);

    if (!$link) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    return $link;
}
