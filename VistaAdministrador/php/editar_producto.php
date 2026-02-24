<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
// Include your database connection file
include("conex.php");
$link = Conectarse();

// Get the product ID from the query parameter
$idProducto = isset($_GET['idProducto']) ? mysqli_real_escape_string($link, $_GET['idProducto']) : null;

// Fetch product details if an ID is provided
$productData = [];
if ($idProducto) {
    $query = "SELECT * FROM ca_productos WHERE Id = '$idProducto'";
    $result = mysqli_query($link, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $productData = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Editar Producto</title>
    <link rel="stylesheet" href="../resources/css/productos.css">
</head>
<body>
<header class="header">
    <a href="admin_control_centre.php" style="text-decoration: none; color: inherit;">
        <h1>Kaffeecito</h1>
    </a>
    <a class="headerButton" href="menu_admin.php">Administrar Menú</a>
        <a class="headerButton" href="manage_users.php">Administrar usuarios</a>
        <a class="headerButton" href="admin_promos.php">Administrar promociones</a>
        <a class="headerButton" href="pedidos.php">Pedidos</a>
    <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
</header>
    </header> 
    <main class="mainPage">
        <div class="form-section">
            <div class="form-container">
                <h2 class="form-title">Editar producto</h2>
                <form id="editForm" action="update_producto.php" method="post" class="edit-form" enctype="multipart/form-data">
                    <input type="hidden" id="idProducto" name="idProducto" value="<?php echo $productData['Id'] ?? ''; ?>">
                    <input type="hidden" id="categoria" name="Categoria" value="<?php echo $productData['Categoria'] ?? ''; ?>">
                    <div class="form-group">
                        <label for="idProducto">ID del producto</label>
                        <input id="idProducto" name="idProducto" class="input disabled-input" type="text" value="<?php echo $productData['Id'] ?? ''; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nombreProducto">Nombre del producto *</label>
                        <input id="nombreProducto" name="Nombre" class="input" type="text" value="<?php echo $productData['Nombre'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <input id="categoria" name="Categoria" class="input disabled-input" type="text" value="<?php echo $productData['Categoria'] ?? ''; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio *</label>
                        <input id="precio" name="Precio" class="input" type="number" step="0.5" min="0" value="<?php echo $productData['Precio'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen">Imagen del producto *</label>
                        <input id="imagen" name="Imagen" class="input" type="file" accept="image/*">
                    </div>
                    <button id="guardar" class="guardar" type="submit">Guardar</button>
                    <button type="button" class="cancelar" onclick="confirmCancel()">Cancelar</button>
                </form>
                <p style="margin-top: 10px; font-size: 0.9em; color: #FF0000;">(*) Este campo es obligatorio.</p>
            </div>
        </div>
    </main>
    <style>
        .disabled-input {
            background-color: #f0f0f0; /* Light gray background */
            color: #888; /* Gray text color */
            border: 1px solid #ccc; /* Light gray border */
            cursor: not-allowed; /* Show "not allowed" cursor */
        }
    </style>
    <script>
        const idProductoSelect = document.getElementById('idProducto');
        const nombreProductoInput = document.getElementById('nombreProducto');
        const categoriaInput = document.getElementById('categoria');
        const precioInput = document.getElementById('precio');
        const idProductoToDelete = document.getElementById('idProductoToDelete');
        const deleteForm = document.getElementById('deleteForm');

        // Fetch product details when an ID is selected
        idProductoSelect.addEventListener('change', function () {
            const idProducto = idProductoSelect.value;

            if (idProducto) {
                fetch('get_producto.php?id=' + idProducto)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Debug: Log the response
                        if (data) {
                            nombreProductoInput.value = data.Nombre || '';
                            categoriaInput.value = data.Categoria || '';
                            precioInput.value = data.Precio || '';
                            idProductoToDelete.value = idProducto; // Set the ID for deletion
                        } else {
                            alert('No se encontraron datos para este ID.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                // Clear the fields if no ID is selected
                nombreProductoInput.value = '';
                categoriaInput.value = '';
                precioInput.value = '';
                idProductoToDelete.value = ''; // Clear the ID for deletion
            }
        });

        precioInput.addEventListener('input', function () {
            if (precioInput.value < 0) {
                alert('El precio no puede ser un número negativo.');
                precioInput.value = ''; // Clear the invalid input
            }
        });

        function confirmCancel() {
            if (confirm("Estás seguro que deseas cancelar la edición?")) {
                location.href = 'menu_admin.php'; // Redirect to the menu page if confirmed
            }
        }

    </script>
</body>
</html>