<?php
include("accessControl.php");
checkAccess("1"); // Only allow administrators (role "2")
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
    header("Location: admin_promos_emp.php");
    exit();
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Promociones - Empleado</title>
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
    <a href="emp_control_centre.php" style="text-decoration: none; color: inherit;">
        <h1>Kaffeecito</h1>
    </a>
    <a class="headerButton" href="menu_empleado.php">Administrar Menú</a>
    <a class="headerButton" href="manage_users_emp.php">Usuarios</a>
    <a class="headerButton" href="admin_promos_emp.php">Administrar promociones</a>
    <a class="headerButton" href="pedidos_empleado.php">Pedidos</a>
    <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
</header>

<div class="table-container">
    <h2>Promociones</h2>
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
                        <img src="../../VistaAdministrador/<?php echo htmlspecialchars($promotion['imagen']); ?>" alt="Promoción" onerror="this.src='../resources/img/default.png';">
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
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>