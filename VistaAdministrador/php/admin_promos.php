<?php
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")
include("conex.php");
$link = Conectarse();

// Fetch all promotions from the database
$query = "SELECT id, imagen, nombre_promocion, visible FROM ca_promociones";
$result = mysqli_query($link, $query);
$promotions = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $promotions[] = $row;
    }
}

// Handle visibility toggle
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['toggleVisibility'])) {
    $id = (int)$_POST['id'];
    $visible = (int)$_POST['visible'];
    $updateQuery = "UPDATE ca_promociones SET visible = $visible WHERE id = $id";
    mysqli_query($link, $updateQuery);
    header("Location: admin_promos.php");
    exit();
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deletePromotion'])) {
    $id = (int)$_POST['id'];
    $deleteQuery = "DELETE FROM ca_promociones WHERE id = $id";
    mysqli_query($link, $deleteQuery);
    header("Location: admin_promos.php");
    exit();
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrar Promociones</title>
    <link rel="stylesheet" href="../resources/css/menu_admin.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: wheat; /* Background color for the page */
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #4C2B18;
            color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .header a.headerButton {
            font-weight: bold;
        }

        .header a.logoutButton:hover {
            background-color: white;
        }

        h2 {
            text-align: center;
            color: #4C2B18;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: white; /* Table background color */
            border: 2px solid #ccc; /* Added 2px border */
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4C2B18;
            color: white;
        }

        img {
            width: 50px; /* Set width to 50px */
            height: 50px; /* Set height to 50px */
            object-fit: cover; /* Ensures the image fits within the dimensions without distortion */
        }

        button {
            background-color: #4C2B18;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #6A3500;
        }

        .action-buttons {
            text-align: right; /* Align the button to the right */
            margin-bottom: 20px; /* Add spacing between the button and the table */
            margin-right: 10%; /* Align to the right with some margin */
        }

        .action-buttons .add-button {
            background-color: #4C2B18;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
        }

        .action-buttons .add-button:hover {
            background-color: #6A3500;
        }

        .table-container {
            width: 80%;
            margin: 20px auto;
            background-color: white; /* Table background color */
            border-radius: 8px; /* Rounded corners */
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

<div class="table-container">
    <h2>Administrar Promociones</h2>
    <div class="action-buttons">
        <a href="anadir_promocion.php" class="add-button">Añadir Promoción</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre de la Promoción</th>
                <th>Visible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promotions as $promotion): ?>
                <tr>
                    <td>
                        <img src="../<?php echo htmlspecialchars($promotion['imagen']); ?>" alt="Promoción" onerror="this.src='../resources/img/default.png';">
                    </td>
                    <td><?php echo htmlspecialchars($promotion['nombre_promocion']); ?></td>
                    <td><?php echo $promotion['visible'] ? 'Sí' : 'No'; ?></td>
                    <td>
                        <!-- Toggle Visibility -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $promotion['id']; ?>">
                            <input type="hidden" name="visible" value="<?php echo $promotion['visible'] ? 0 : 1; ?>">
                            <button type="submit" name="toggleVisibility">
                                <?php echo $promotion['visible'] ? 'Ocultar' : 'Mostrar'; ?>
                            </button>
                        </form>
                        <!-- Edit Promotion -->
                        <form method="GET" action="editar_promocion.php" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $promotion['id']; ?>">
                            <button type="submit">Editar</button>
                        </form>
                        <!-- Delete Promotion -->
                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta promoción?');">
                            <input type="hidden" name="id" value="<?php echo $promotion['id']; ?>">
                            <button type="submit" name="deletePromotion">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>