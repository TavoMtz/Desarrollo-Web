<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
// Include your database connection file
include("conex.php");
$link = Conectarse();

// Fetch product IDs, names, prices, and images from the database
$query = "SELECT Id, Nombre, Precio, Imagen FROM ca_productos";
$result = mysqli_query($link, $query);
$productData = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $productData[] = $row;
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
    <title>Kaffeecito - Añadir Promoción</title>
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
    <main class="mainPage">
        <div class="form-section">
            <div class="form-container">
                <h2 class="form-title">Añadir promoción</h2>
                <form id="addPromoForm" action="insert_promocion.php" method="post" class="edit-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nombrePromocion">Nombre de la promoción *</label>
                        <input id="nombrePromocion" name="nombrePromocion" class="input" type="text" placeholder="Nombre de la promoción" required>
                    </div>
                    <div class="form-group">
                        <label for="idProducto1">ID del producto 1 *</label>
                        <select id="idProducto1" name="idProducto1" class="input" required>
                            <option value="">Seleccione un ID</option>
                            <?php foreach ($productData as $product) : ?>
                                <option value="<?php echo $product['Id']; ?>">
                                    <?php echo $product['Id'] . ' - ' . $product['Nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idProducto2">ID del producto 2 *</label>
                        <select id="idProducto2" name="idProducto2" class="input" required>
                            <option value="">Seleccione un ID</option>
                            <?php foreach ($productData as $product) : ?>
                                <option value="<?php echo $product['Id']; ?>">
                                    <?php echo $product['Id'] . ' - ' . $product['Nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idProducto3">ID del producto 3</label>
                        <select id="idProducto3" name="idProducto3" class="input">
                            <option value="">Seleccione un ID</option>
                            <?php foreach ($productData as $product) : ?>
                                <option value="<?php echo $product['Id']; ?>">
                                    <?php echo $product['Id'] . ' - ' . $product['Nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idProducto4">ID del producto 4</label>
                        <select id="idProducto4" name="idProducto4" class="input">
                            <option value="">Seleccione un ID</option>
                            <?php foreach ($productData as $product) : ?>
                                <option value="<?php echo $product['Id']; ?>">
                                    <?php echo $product['Id'] . ' - ' . $product['Nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio *</label>
                        <input id="precio" name="precio" class="input" type="number" step="0.01" placeholder="Precio" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen">Imagen de la promoción *</label>
                        <input id="imagen" name="imagen" class="input" type="file" accept="image/*" required>
                    </div>
                    <button id="guardar" class="guardar" type="submit">Guardar</button>
                    <button type="button" class="cancelar" onclick="confirmCancel()">Cancelar</button>
                </form>
                <p style="margin-top: 10px; font-size: 0.9em; color: #FF0000;">(*) Este campo es obligatorio.</p>
            </div>
        </div>
    </main>
    <script>
        function confirmCancel() {
            if (confirm("Estás seguro que no deseas añadir una promoción?")) {
                location.href = 'menu_admin.php';
            }
        }
    </script>
</body>
</html>