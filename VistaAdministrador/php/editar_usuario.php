<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
include("conex.php");
$link = Conectarse();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['idUsuario'])) {
    $idUsuario = (int)$_GET['idUsuario'];

    // Fetch user data
    $query = "SELECT id, nombre, apellidos, email, susuario FROM ca_usuarios WHERE id = $idUsuario";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        echo "Usuario no encontrado.";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idUsuario'])) {
    $idUsuario = (int)$_POST['idUsuario'];
    $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($link, $_POST['apellidos']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $susuario = (int)$_POST['susuario'];

    // Update user data
    $query = "UPDATE ca_usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email', susuario = $susuario WHERE id = $idUsuario";
    if (mysqli_query($link, $query)) {
        header("Location: manage_users.php?success=editado");
        exit();
    } else {
        echo "Error al actualizar el usuario.";
    }
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../resources/css/menu_admin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: wheat;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mainPage {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4C2B18;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container input, .form-container select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            background-color: #4C2B18;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #6A3500;
        }

        .form-container a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #4C2B18;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        .cancel-button {
            display: inline-block;
            background-color: #ccc; /* Light gray background */
            color: #4C2B18; /* Text color */
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel-button:hover {
            background-color: #bbb; /* Darker gray on hover */
        }
    </style>
</head>
<body>
    <main class="mainPage">
        <div class="form-container">
            <h2>Editar Usuario</h2>
            <form method="POST" action="editar_usuario.php">
                <input type="hidden" name="idUsuario" value="<?php echo $user['id']; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <label for="susuario">Rol:</label>
                <select id="susuario" name="susuario" required>
                    <option value="0" <?php echo $user['susuario'] == 0 ? 'selected' : ''; ?>>Cliente</option>
                    <option value="1" <?php echo $user['susuario'] == 1 ? 'selected' : ''; ?>>Empleado</option>
                    <option value="2" <?php echo $user['susuario'] == 2 ? 'selected' : ''; ?>>Administrador</option>
                </select>
                <button type="submit">Guardar Cambios</button>
                <a href="manage_users.php" class="cancel-button">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>