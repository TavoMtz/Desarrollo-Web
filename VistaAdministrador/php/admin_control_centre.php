<?php
session_start();
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")

// Check if the user's name is already in the session
if (!isset($_SESSION['nombre'])) {
    include("conex.php");
    $link = Conectarse();

    // Fetch the user's name from the database using their session ID
    $userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
    $query = "SELECT nombre FROM ca_usuarios WHERE id = $userId";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['nombre'] = $row['nombre']; // Store the name in the session
    } else {
        $_SESSION['nombre'] = 'Admin'; // Fallback if the user is not found
    }
}

// Use the name from the session
$nombreUsuario = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administrador</title>
    <link rel="stylesheet" href="../resources/css/admin_control.css">
</head>
<body>
    <header>
        <a href="admin_control_centre.php">
            <h1>Kaffeecito</h1>
        </a>
        <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
    </header>
    <div class="control-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h2>
        <div class="control-buttons">
            <a href="menu_admin.php">Administrar productos</a>
            <a href="pedidos.php">Ver ordenes</a>
            <a href="anadir_producto.php">Añadir productos</a>
            <a href="admin_promos.php">Administrar promociones</a>
            <a href="manage_users.php">Administrar usuarios</a>
        </div>
    </div>
</body>
</html>