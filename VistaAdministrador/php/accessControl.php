<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function checkAccess($requiredRole) {
    if (!isset($_SESSION["tipo"])) {
        // Redirect to login if not logged in
        header("Location: ../../VistaUsuarios/formIniSesion/formsInicioSesion.html?error=no_autorizado");
        exit();
    }

    if ($_SESSION["tipo"] != $requiredRole) {
        // Redirect to the appropriate page based on the user's role
        switch ($_SESSION["tipo"]) {
            case "0":
                header("Location: ../../index.php?error=no_autorizado");
                break;
            case "1":
                header("Location: ../../VistaEmpleado/php/menu_empleado.php?error=no_autorizado");
                break;
            case "2":
                header("Location: ../../VistaAdministrador/php/admin_control_centre.php?error=no_autorizado");
                break;
            default:
                header("Location: ../../index.php?error=rol_desconocido");
                break;
        }
        exit();
    }
}
?>