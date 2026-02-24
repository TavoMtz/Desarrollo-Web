<?php 
session_start(); // Inicia la sesión

// Verifica si la variable de sesión "autentificado" está definida y es "SI"
if (!isset($_SESSION["autentificado"]) || $_SESSION["autentificado"] != "SI") { 
    // Si no está autenticado, redirige al login
    header("Location: ../../index.php"); // Cambia la ruta según tu estructura de carpetas
    exit();
}
?>
