<?php
include("accessControl.php");
checkAccess("1"); // Only allow administrators (role "2")
include("conex.php");
$link = Conectarse();

// Pagination logic
$usersPerPage = 5; // Number of users to display per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $usersPerPage; // Calculate the offset for the query

// Search and sort logic
$search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : 'asc';

// Fetch total number of users (with search filter)
$totalUsersQuery = "SELECT COUNT(*) AS total FROM ca_usuarios WHERE nombre LIKE '%$search%' OR email LIKE '%$search%'";
$totalUsersResult = mysqli_query($link, $totalUsersQuery);
$totalUsersRow = mysqli_fetch_assoc($totalUsersResult);
$totalUsers = $totalUsersRow['total'];

// Fetch user data for the current page (with search and sort)
$query = "SELECT id, nombre, apellidos, email, susuario 
          FROM ca_usuarios 
          WHERE nombre LIKE '%$search%' OR email LIKE '%$search%' 
          ORDER BY susuario $sort 
          LIMIT $offset, $usersPerPage";
$result = mysqli_query($link, $query);
$users = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Calculate total pages
$totalPages = ceil($totalUsers / $usersPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Gestión de Usuarios</title>
    <link rel="stylesheet" href="../resources/css/menu_admin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: wheat;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mainPage {
            display: flex;
            width: 100%; /* Reduce width to leave space on the edges */
            gap: 0px; /* Add spacing between the action buttons and the products section */
            margin-top: 20px; /* Add margin to separate the main content from the header */
        }
        

        .users-container {
            width: 100%;
            max-width: 1200px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            
        }

        .users-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4C2B18;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th, .users-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .users-table th {
            background-color: #4C2B18;
            color: white;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            text-decoration: none;
            color: white;
            background-color: #4C2B18;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #6A3500;
        }

        .pagination .active {
            background-color: #6A3500;
            pointer-events: none;
        }

        .search-sort-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-sort-container form {
            display: flex;
            gap: 10px;
        }

        .search-sort-container input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-sort-container button {
            background-color: #4C2B18;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-sort-container button:hover {
            background-color: #6A3500;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #4C2B18;
            color: white;
            padding: 10px 20px; /* Adjust padding for compactness */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        body {
            margin: 0;
            padding-top: 80px; /* Add padding to push content below the header */
            font-family: Arial, sans-serif;
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
    <main class="mainPage">
        <div class="users-container">
            <h2>Gestión de Usuarios</h2>
            <div class="search-sort-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Buscar por nombre o email" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Buscar</button>
                </form>
                <form method="GET" action="">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" name="sort" value="<?php echo $sort === 'asc' ? 'desc' : 'asc'; ?>">
                        Ordenar por Rol (<?php echo $sort === 'asc' ? 'Ascendente' : 'Descendente'; ?>)
                    </button>
                </form>
            </div>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['nombre']; ?></td>
                                <td><?php echo $user['apellidos']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <?php
                                    if ($user['susuario'] == "1") {
                                        echo "Empleado";
                                    } elseif ($user['susuario'] == "2") {
                                        echo "Administrador";
                                    } else {
                                        echo "Cliente";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Anterior</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Siguiente</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
<?php
mysqli_close($link);
?>