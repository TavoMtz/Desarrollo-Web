<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
include("conex.php");
$link = Conectarse();

// Consulta para obtener todos los pedidos con filtros
$filtro_fecha = isset($_GET['fecha']) ? "AND DATE(fecha) = '".mysqli_real_escape_string($link, $_GET['fecha'])."'" : "";
$filtro_nombre = isset($_GET['cliente']) ? "AND nombre LIKE '%".mysqli_real_escape_string($link, $_GET['cliente'])."%'" : "";

$query = "SELECT id, nombre, precio, cantidad, fecha
            FROM ca_pedidos
            WHERE 1=1 $filtro_fecha $filtro_nombre
            ORDER BY fecha DESC";
$result = mysqli_query($link, $query);

// Consulta para el total general
$query_total = "SELECT SUM(precio*cantidad) as total FROM ca_pedidos WHERE 1=1 $filtro_fecha $filtro_nombre";
$result_total = mysqli_query($link, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Administración</title>
    <link rel="stylesheet" href="../resources/css/vistaAdmin.css">
</head>
<body>
    <header class="header">
        <h1>Kaffeecito</h1>
        <nav>
        <a class="headerButton" href="menu_admin.php">Administrar Menú</a>
        <a class="headerButton" href="manage_users.php">Administrar usuarios</a>
        <a class="headerButton" href="admin_promos.php">Administrar promociones</a>
        <a class="headerButton" href="pedidos.php">Pedidos</a>
        <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="admin-container">
        <h2 class="admin-title">Panel de Administración</h2>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'pedido_eliminado'): ?>
            <div class="success-message">Pedido eliminado correctamente.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">Error: <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form method="GET" action="vistaAdmin.php" class="filter-form">
            <input type="date" name="fecha" class="admin-input" value="<?= isset($_GET['fecha']) ? $_GET['fecha'] : '' ?>">
            <input type="text" name="cliente" placeholder="Filtrar por producto" class="admin-input"
                   value="<?= isset($_GET['cliente']) ? htmlspecialchars($_GET['cliente']) : '' ?>">
            <button type="submit" class="admin-button">Aplicar Filtros</button>
            <button type="button" class="admin-button" onclick="window.print()">Imprimir Reporte</button>
        </form>

        <div class="pedidos-table-container">
            <table class="pedidos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio Unit.</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha/Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($pedido = mysqli_fetch_assoc($result)): ?>
                            <?php $total = $pedido['precio'] * $pedido['cantidad']; ?>
                            <tr>
                                <td><?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['nombre']) ?></td>
                                <td>$<?= number_format($pedido['precio'], 2) ?></td>
                                <td><?= $pedido['cantidad'] ?></td>
                                <td>$<?= number_format($total, 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></td>
                                <td>
                                    <a href="editarPedido.php?id=<?= $pedido['id'] ?>" class="action-button edit">Editar</a>
                                    <a href="eliminarPedido.php?id=<?= $pedido['id'] ?>" class="action-button delete"
                                       onclick="return confirm('¿Eliminar este pedido?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No se encontraron pedidos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Total General</strong></td>
                        <td colspan="3"><strong>$<?= number_format($row_total['total'] ?? 0, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>

    <script src="../resources/js/admin.js"></script>
</body>
</html>

<?php mysqli_close($link); ?>