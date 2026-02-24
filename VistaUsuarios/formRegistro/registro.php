<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre =  trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $c_email = trim($_POST['c_email']);
    $contraseña = trim($_POST['contraseña']);
    $c_contraseña = trim($_POST['c_contraseña']);

if ($email === $c_email && $contraseña === $c_contraseña) {
    include("conect.php");
    $link = conectarse();
    $hash_password = hash('sha256', $contraseña);
    $result = mysqli_query($link, "INSERT INTO ca_usuarios (nombre, apellidos, email, pass) VALUES ('$nombre','$apellidos', '$email', '$hash_password')");

    if ($result === TRUE) {
        echo "Registro exitoso";
        header("Location: ../../index.php");
    } else {
        echo "Error: " . mysqli_error($link);
    }
    } else {
    echo "<script>alert('Las contraseñas o los correos no coinciden');
          window.location.href = '../formRegistro/formsRegistro.html';
          </script>";
}
}

