<?php
session_start();
$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;
?>
<?php if (isset($_SESSION["login_success"])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; margin: 20px; border-radius: 5px; text-align:center;">
        ✅ ¡Sesión iniciada con éxito!
    </div>
    <?php unset($_SESSION["login_success"]); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Kaffeecito</title>
    <link rel="stylesheet" href="index.css">
    <link rel="apple-touch-icon" sizes="180x180" href="VistaUsuarios/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="VistaUsuarios/img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="VistaUsuarios/img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="VistaUsuarios/img/favicon_io/site.webmanifest">
</head>

<header class="header">
    <h1>Kaffeecito</h1>
    <a class="headerButton" href="VistaUsuarios/menu.php">Menú</a>
    <a class="headerButton" href="VistaUsuarios/aboutus.html">Acerca de Nosotros</a>
    <a class="headerButton" href="VistaUsuarios/promok.html">Promos de Temporada</a>

    <?php if ($usuario): ?>
        <span class="headerButton">Bienvenido,Empleado  <?= htmlspecialchars($usuario) ?></span>
        <a class="headerButton" href="VistaUsuarios/logout.php"> Cerrar Sesión </a>
    <?php else: ?>
        <a class="headerButton" href="VistaUsuarios/formIniSesion/formsInicioSesion.html">Iniciar Sesión</a>
    <?php endif; ?>
</header>

<body>
    <section class="seccionA">
        <h2>El café que te hace sentir en casa</h2>
        <div class="boxA">
            Disfruta de una experiencia única con nuestras deliciosas bebidas un ambiente acogedor.
        </div>
    </section>

    <section class="visit-section">
        <div class="visit-text">¡Visítanos!</div>
        <img src="VistaUsuarios/img/cafk.png" class="visit-image">
    </section>

    <section class="info-section">
        <div class="info-item">Dirección: Cam. a la Carcaña 2403, 72810 San Pedro Cholula, Puebla</div>
        <div class="info-item">Horario: Lun - Vie: 8:30 - 20:00 | Sab: 9:00 - 15:00</div>
    </section>

    <section class="footer-section">
        <div class="footer-content">
            <div class="footer-info">
                <span>📞 CONTACTO: 222-5552902</span>
                <span>✉️ CORREO: info@kaffeecito.mx</span>
                <span>🐾 Pet Friendly</span>
            </div>
            <div class="footer-copy">© DERECHOS RESERVADOS</div>
        </div>
    </section>
</body>

</html>