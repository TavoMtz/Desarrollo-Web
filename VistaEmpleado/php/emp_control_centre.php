<?php
session_start();
include("accessControl.php");
checkAccess("1"); // Only allow employees (role "1")

// Check if the user's name is already in the session
if (!isset($_SESSION['nombre'])) {
    include("conex.php");
    $link = Conectarse();

    // Fetch the user's name from the database using their session ID
    $userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

    if ($userId > 0) {
        $query = "SELECT nombre FROM ca_usuarios WHERE id = $userId";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['nombre'] = $row['nombre']; // Store the name in the session
        } else {
            $_SESSION['nombre'] = 'Empleado'; // Fallback if the user is not found
        }
    } else {
        $_SESSION['nombre'] = 'Empleado'; // Fallback if no user ID is set
    }
}

// Use the name from the session
$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Empleado';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de empleado</title>
    <link rel="stylesheet" href="../resources/css/emp_control.css">
</head>
<body>
    <header>
        <a href="emp_control_centre.php">
            <h1>Kaffeecito</h1>
        </a>
        <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
    </header>
    <div class="control-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h2>
        <div class="control-buttons">
            <a href="menu_empleado.php">Administrar menú</a>
            <a href="pedidos_empleado.php">Ver pedidos</a>
            <a href="admin_promos_emp.php">Administrar promociones</a>
            <a href="manage_users_emp.php">Administrar usuarios</a>
        </div>
    </div>
</body>
</html>