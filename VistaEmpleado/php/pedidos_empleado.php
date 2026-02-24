<?php
include("conex.php");
$link = Conectarse();

// Consulta para obtener todos los pedidos
$query = "SELECT id, nombre, precio, cantidad, fecha
            FROM ca_pedidos
            ORDER BY fecha DESC";
$result = mysqli_query($link, $query);

// Consulta para el total general
$query_total = "SELECT SUM(precio*cantidad) as total FROM ca_pedidos";
$result_total = mysqli_query($link, $query_total);
$row_total = mysqli_fetch_assoc($result_total);

// Mensajes de éxito o error (si vienen por la URL)
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Consulta para obtener la lista de productos para el formulario
$query_productos = "SELECT Id, Nombre FROM ca_productos ORDER BY Nombre ASC";
$result_productos = mysqli_query($link, $query_productos);
$productos = mysqli_fetch_all($result_productos, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Vista Empleado</title>
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
            <a class="headerButton" href="menu.php">Menú</a>
            <a class="headerButton" href="vistaEmpleado.php">Pedidos</a>
            <a class="headerButton" href="../../VistaUsuarios/aboutus.html">Acerca de Nosotros</a>
            <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="employee-container">
        <h2 class="employee-title">Nuevo Pedido</h2>

        <div class="new-order-container">
            <form action="procesarPedido.php" method="post" class="order-form">
                <div class="form-group">
                    <label for="producto">Producto:</label>
                    <select id="producto" name="producto" class="employee-input" required>
                        <option value="">Seleccionar Producto</option>
                        <?php if ($productos): ?>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= htmlspecialchars($producto['Nombre']) ?>">
                                    <?= htmlspecialchars($producto['Nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" class="employee-input" value="1" min="1" required>
                </div>
                <button type="submit" class="employee-button">Realizar Pedido</button>
            </form>
        </div>

        <?php if ($mensaje): ?>
            <div class="success-message"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <h2 class="employee-title">Vista de Pedidos</h2>

        <div class="filter-form">
            <label for="search-pedido">Buscar Pedido:</label>
            <input type="text" id="search-pedido" class="admin-input" placeholder="Por ID o Nombre del Producto">
            <button onclick="window.print()" class="admin-button">Imprimir Reporte</button>
        </div>

        <div class="pedidos-table-container">
            <table class="pedidos-table" id="active-orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha/Hora</th>
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
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No se encontraron pedidos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total General</strong></td>
                        <td colspan="2"><strong>$<?= number_format($row_total['total'] ?? 0, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-pedido');
            const orderTableBody = document.querySelector('#active-orders-table tbody');
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