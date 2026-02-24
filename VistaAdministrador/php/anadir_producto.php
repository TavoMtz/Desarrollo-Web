<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Añadir Producto</title>
    <link rel="stylesheet" href="../resources/css/productos.css">
</head>
<body>
<header class="header">
    <a href="admin_control_centre.php" style="text-decoration: none; color: inherit;">
        <h1>Kaffeecito</h1>
    </a>
    <a class="headerButton" href="menu_admin.php">Administrar Menú</a>
        <a class="headerButton" href="manage_users.php">Administrar usuarios</a>
        <a class="headerButton" href="php/admin_promos.php">Administrar promociones</a>
        <a class="headerButton" href="pedidos.php">Pedidos</a>
    <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
</header>
    <main class="mainPage">
        <div class="form-section">
            <div class="form-container">
                <h2 class="form-title">Añadir producto</h2>
                <form id="addForm" action="insert_producto.php" method="post" class="edit-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="input" required>
                            <option value="bebidas">Bebidas</option>
                            <option value="alimentos">Alimentos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <laber for="Nombre">Nombre del producto *</label>
                        <input id="Nombre" name="Nombre" class="input" type="text" placeholder="Nombre del producto" required>
                    </div>
                    <div class="form-group">
                        <label for="Precio">Precio *</label>
                        <input id="Precio" name="Precio" class="input" type="number" step="0.50" min="0" placeholder="Precio" required>
                    </div>
                    <div class="form-group">
                        <label for="Imagen">Imagen del producto *</label>
                        <input id="Imagen" name="Imagen" class="input" type="file" accept="image/*" required>
                    </div>
                    <button id="guardar" class="guardar" type="submit">Guardar</button>
                    <button type="button" class="cancelar" onclick="confirmCancel()">Cancelar</button>
                </form>
                <p style="margin-top: 10px; font-size: 0.9em; color: #FF0000;">(*) Este campo es obligatorio.</p>
            </div>
        </div>
    </main>
    <script>
        const precioInput = document.getElementById('Precio');

        precioInput.addEventListener('input', function () {
            if (precioInput.value < 0) {
                alert('El precio no puede ser un número negativo.');
                precioInput.value = ''; // Clear the invalid input
            }
        });

        function confirmCancel() {
            if (confirm("Estás seguro que no deseas añadir un producto?")) {
                location.href = 'menu_admin.php';
            }
        }
    </script>
</body>
</html>