<?php
include("conex.php");
$link = Conectarse();
if (!$link) {
    die("Error de conexión en vistaAdmin.php: " . mysqli_connect_error());
}

// Consulta base para obtener todos los pedidos
$query_base = "SELECT id, nombre, precio, cantidad, fecha FROM ca_pedidos";

// Filtros
$filtro_fecha = isset($_GET['fecha']) ? "AND DATE(fecha) = '".mysqli_real_escape_string($link, $_GET['fecha'])."'" : "";
$filtro_nombre_get = isset($_GET['cliente']) ? "AND nombre LIKE '%".mysqli_real_escape_string($link, $_GET['cliente'])."%'" : "";
$filtro_busqueda = isset($_GET['search-pedido']) ? "AND (id LIKE '%".mysqli_real_escape_string($link, $_GET['search-pedido'])."%' OR nombre LIKE '%".mysqli_real_escape_string($link, $_GET['search-pedido'])."%')" : "";

// Consulta principal con filtros
$query = "$query_base WHERE 1=1 $filtro_fecha $filtro_nombre_get $filtro_busqueda ORDER BY fecha DESC";
$result = mysqli_query($link, $query);

// Consulta para el total general con filtros
$query_total = "SELECT SUM(precio*cantidad) as total FROM ca_pedidos WHERE 1=1 $filtro_fecha $filtro_nombre_get $filtro_busqueda";
$result_total = mysqli_query($link, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Administración</title>
    <link rel="stylesheet" href="../resources/css/vistaAdmin.css">
    <style>
        /* Estilos adicionales para la sección de búsqueda */
        .filter-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .filter-form label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Kaffeecito</h1>
        <nav>
        <a class="headerButton" href="menu_admin.php">Menú</a>
        |
        <a class="headerButton" href="../../VistaUsuarios/aboutus.html">Acerca de Nosotros</a>s
        |
        <a class="headerButton" href="../../VistaUsuarios/promok.html">Promos de Temporada</a>
        |
        <a class="headerButton" href="vistaAdmin.php">Pedidos</a>
        |
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
            <label for="fecha">Filtrar por Fecha:</label>
            <input type="date" name="fecha" class="admin-input" value="<?= isset($_GET['fecha']) ? $_GET['fecha'] : '' ?>">
            <label for="cliente">Filtrar por Producto:</label>
            <input type="text" name="cliente" placeholder="Nombre del producto" class="admin-input"
                   value="<?= isset($_GET['cliente']) ? htmlspecialchars($_GET['cliente']) : '' ?>">
            <label for="search-pedido">Buscar Pedido:</label>
            <input type="text" id="search-pedido" name="search-pedido" class="admin-input" placeholder="Por ID o Nombre del Producto"
                   value="<?= isset($_GET['search-pedido']) ? htmlspecialchars($_GET['search-pedido']) : '' ?>">
            <button type="submit" class="admin-button">Aplicar Filtros</button>
            <button type="button" class="admin-button" onclick="window.print()">Imprimir Reporte</button>
        </form>

        <div class="pedidos-table-container">
            <table class="pedidos-table" id="admin-orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
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
                            <tr data-pedido-id="<?= $pedido['id'] ?>">
                                <td><?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['nombre']) ?></td>
                                <td><?= $pedido['cantidad'] ?></td>
                                <td>$<?= number_format($total, 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></td>
                                <td>
                                    <a href="eliminarPedido.php?id=<?= $pedido['id'] ?>" class="action-button delete"
                                       onclick="return confirm('¿Eliminar este pedido?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No se encontraron pedidos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total General</strong></td>
                        <td colspan="3"><strong>$<?= number_format($row_total['total'] ?? 0, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-pedido');
            const orderTableBody = document.querySelector('#admin-orders-table tbody');
            const rows = Array.from(orderTableBody.querySelectorAll('tr'));

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const id = row.cells[0].textContent.toLowerCase();
                    const producto = row.cells[1].textContent.toLowerCase();
                    row.style.display = id.includes(searchTerm) || producto.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($link); ?>