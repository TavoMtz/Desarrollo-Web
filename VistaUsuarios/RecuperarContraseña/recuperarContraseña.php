<?php
// Datos de conexión a la base de datos (reemplázalos)
include("conect.php");
$link = Conectarse();

// Crear conexión

// Verificar conexión
if ($link->connect_error) {
    die("Conexión fallida: " . $link->connect_error);
}

// Obtener el correo del formulario
$correo = $_POST['correo'];

// Consulta para buscar el correo
$result = mysqli_query($link, "SELECT pass FROM ca_usuarios WHERE email = '$correo'");

if ($result->num_rows > 0) {
    // Usuario encontrado
    //$row = $result->fetch_assoc(); //Devuelve clave valor la clave es el nombre de la columna 
 //$contraseña = $row['pass'];

    //Enviar correo usando la funcion mail()
   // $asunto = "Recuperación de contraseña";
  //  $mensaje = "Tu contraseña es: $contraseña";
   // $cabeceras = "From: soporte.kaffessito@gmail.com"; //Cambia correo

    //if (mail($correo, $asunto, $mensaje, $cabeceras)) {
        echo "<script>
        setTimeout(function() {
            window.location.href = 'recuperarContraseñaExito.html';
        }, 2000); // 2000 milisegundos = 2 segundos
      </script>";
   // }
} else {
    echo "<script>
            setTimeout(function() {
                window.location.href = 'recuperarContraseñaFracaso.html';
            }, 2000);
          </script>";
}
$link->close();
