<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
include("conex.php");
$link = Conectarse();

// Check if an ID is provided via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No se proporcionó un ID válido para editar.";
    exit();
}

$idPromocion = (int)$_GET['id'];

// Fetch promotion details from the database
$queryPromocion = "SELECT * FROM ca_promociones WHERE id = $idPromocion";
$resultPromocion = mysqli_query($link, $queryPromocion);
if (!$resultPromocion || mysqli_num_rows($resultPromocion) === 0) {
    echo "No se encontró la promoción con el ID proporcionado.";
    exit();
}
$promocion = mysqli_fetch_assoc($resultPromocion);

// Fetch product IDs, names, prices, and images from the database
$queryProductos = "SELECT Id, Nombre, Precio, Imagen FROM ca_productos";
$resultProductos = mysqli_query($link, $queryProductos);
$productData = [];
if ($resultProductos) {
    while ($row = mysqli_fetch_assoc($resultProductos)) {
        $productData[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombrePromocion = mysqli_real_escape_string($link, $_POST['nombrePromocion']);
    $idProducto1 = (int)$_POST['idProducto1'];
    $idProducto2 = (int)$_POST['idProducto2'];
    $idProducto3 = !empty($_POST['idProducto3']) ? (int)$_POST['idProducto3'] : null;
    $idProducto4 = !empty($_POST['idProducto4']) ? (int)$_POST['idProducto4'] : null;
    $precio = (float)$_POST['precio'];

    // Handle image upload
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $targetDir = __DIR__ . "/../resources/img/promos/";
        $imagen = $targetDir . basename($_FILES['imagen']['name']);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
            echo "Error al subir la imagen.";
            exit();
        }

        $imagen = "../resources/img/promos/" . basename($_FILES['imagen']['name']);
    }

    // Update promotion in the database
    $query = "UPDATE ca_promociones 
              SET nombre_promocion = '$nombrePromocion', 
                  id_producto1 = $idProducto1, 
                  id_producto2 = $idProducto2, 
                  id_producto3 = " . ($idProducto3 ? $idProducto3 : "NULL") . ", 
                  id_producto4 = " . ($idProducto4 ? $idProducto4 : "NULL") . ", 
                  precio = $precio" . 
                  ($imagen ? ", imagen = '$imagen'" : "") . " 
              WHERE id = $idPromocion";

    if (mysqli_query($link, $query)) {
        header("Location: menu_admin.php?success=promocion_editada");
        exit();
    } else {
        echo "Error al actualizar la promoción.";
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Editar Promoción</title>
    <link rel="stylesheet" href="../resources/css/productos.css">
    <style>
        .uneditable {
            background-color: #f0f0f0; /* Light gray background */
            color: #888; /* Gray text color */
            border: 1px solid #ccc; /* Optional border */
            cursor: not-allowed; /* Change cursor to indicate non-editable */
        }
    </style>
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
            <h2 class="form-title">Editar Promoción</h2>
            <form id="editPromoForm" action="editar_promocion.php?id=<?php echo $idPromocion; ?>" method="post" class="edit-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="idPromocion">ID de la promoción</label>
                    <input id="idPromocion" name="idPromocion" class="input uneditable" type="text" value="<?php echo $promocion['id']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="nombrePromocion">Nombre de la promoción *</label>
                    <input id="nombrePromocion" name="nombrePromocion" class="input" type="text" value="<?php echo htmlspecialchars($promocion['nombre_promocion']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="idProducto1">ID del producto 1 *</label>
                    <select id="idProducto1" name="idProducto1" class="input" required>
                        <option value="">Seleccione un ID</option>
                        <?php foreach ($productData as $product) : ?>
                            <option value="<?php echo $product['Id']; ?>" <?php echo $product['Id'] == $promocion['id_producto1'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $product['Id']; ?>" <?php echo $product['Id'] == $promocion['id_producto2'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $product['Id']; ?>" <?php echo $product['Id'] == $promocion['id_producto3'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $product['Id']; ?>" <?php echo $product['Id'] == $promocion['id_producto4'] ? 'selected' : ''; ?>>
                                <?php echo $product['Id'] . ' - ' . $product['Nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input id="precio" name="precio" class="input" type="number" step="0.01" value="<?php echo $promocion['precio']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen de la promoción</label>
                    <input id="imagen" name="imagen" class="input" type="file" accept="image/*">
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
        if (confirm("Estás seguro que deseas cancelar la edición?")) {
            location.href = 'menu_admin.php';
        }
    }
</script>
</body>
</html>