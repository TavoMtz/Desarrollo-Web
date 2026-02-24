<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../resources/css/inicioSesion.css">
</head>
<body>
    <header class="header">
        <h1>Kaffeecito</h1>
        <nav class="nav-links">
            <a class="headerButton" href="menu.php">Menú</a>
            |
            <a class="headerButton" href="formsInicioSesion.html">Acerca de Nosotros</a>
            |
            <a class="headerButton" href="formsInicioSesion.html">Promos de Temporada</a>
            |
            <a class="headerButton regresarButton" href="index.php">Regresar</a>
        </nav>
    </header>
    <main class="mainPage">
        <div class="login-container">
            <h1>Inicio de Sesión</h1>
            <?php
            // Display an error message if login fails
            if (isset($_GET['errorusuario']) && $_GET['errorusuario'] == 1) {
                echo '<p style="color: red;">Usuario o contraseña incorrectos. Inténtalo de nuevo.</p>';
            }
            ?>
            <form action="control.php" method="POST">
                <div class="form-group">
                    <label for="ID">Usuario:</label>
                    <input type="text" id="ID" name="ID" required>
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña:</label>
                    <input type="password" id="pass" name="pass" required>
                </div>
                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    </main>
</body>
</html>