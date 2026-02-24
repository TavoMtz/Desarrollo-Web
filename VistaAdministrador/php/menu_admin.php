<?php
session_start();
include("accessControl.php");
checkAccess("2"); // Only allow administrators (role "2")

// Redirect to login if the session is not active
if (!isset($_SESSION['tipo'])) {
    header("Location: ../../VistaUsuarios/formIniSesion/formsInicioSesion.html?error=no_autorizado");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Menú de Productos</title>
    <link rel="stylesheet" href="../resources/css/menu_admin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: wheat;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mainPage {
            display: flex;
            justify-content: flex-start; /* Align items horizontally */
            align-items: flex-start; /* Align items at the top */
            width: 100%; /* Reduce width to leave space on the edges */
            gap: 0px; /* Add spacing between the action buttons and the products section */
            margin-top: 0px; /* Add margin to separate the main content from the header */
            overflow-x: hidden;
        }

        .category-container {
            width: 100%;
            text-align: center; /* Center-align the content inside the container */
            margin-bottom: 20px; /* Add spacing between categories */
        }

        .category-title {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0 auto; /* Center horizontally */
            text-align: center; /* Center the text inside the title */
            color: white;
            background-color: rgba(76, 43, 24, 0.8);
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block; /* Ensure the title takes only the necessary width */
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 8px;
            margin: 0 auto;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px; /* Add spacing between categories */
        }

        .menu-item {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between; /* Ensure proper spacing between elements */
            height: auto; /* Allow the height to adjust based on content */
            min-height: 250px; /* Set a minimum height for consistency */
            overflow: hidden; /* Ensure content stays within the card */
        }

        .menu-item img {
            width: 100px; /* Set the width to 25px */
            height: 100px; /* Set the height to 25px */
            border-radius: 8px; /* Keep the rounded corners */
            object-fit: cover; /* Ensure the image fits within the dimensions */
        }

        .menu-item h3 {
            margin: 10px 0; /* Add spacing around the name */
            text-align: center; /* Center-align the text */
            word-wrap: break-word; /* Allow long words to break and wrap to the next line */
            width: 100%; /* Ensure the text stays within the card width */
            box-sizing: border-box; /* Include padding and border in the width */
        }

        .action-buttons {
            display: flex;
            flex-direction: column; /* Stack the buttons vertically */
            gap: 10px; /* Add spacing between buttons */
            align-items: flex-start; /* Align buttons to the left */
            margin-top: 0; /* Ensure alignment with the first category */
            position: sticky; /* Keep the buttons visible while scrolling */
            top: 120px; /* Adjust based on the header height */
        }

        .action-buttons a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            background-color: #4C2B18;
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 200px; /* Set a fixed width for consistency */
            text-align: center; /* Center the text inside the button */
        }

        .action-buttons a:hover {
            background-color: #6A3500;
        }

        .products-section {
            flex: 1; /* Allow the products section to take up the remaining space */
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .search-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            text-align: center;
        }

        #search-bar {
            width: 80%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        #sort-icon {
            cursor: pointer;
            width: 30px;
            height: 30px;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar este producto?");
        }

        function confirmUnavailable() {
            return confirm("Estás seguro que deseas cambiar el estado del producto a 'No disponible'?");
        }

        function confirmDisponible() {
            return confirm("Estás seguro que deseas cambiar el estado del producto a 'Disponible'?");
        }

        let isAscending = true; // Flag to track the sorting order

        function filterProducts() {
            const searchQuery = document.getElementById('search-bar').value.toLowerCase();
            const categories = document.querySelectorAll('.category-container');

            categories.forEach(category => {
                const products = category.querySelectorAll('.menu-item');
                let hasVisibleItems = false;

                products.forEach(product => {
                    const productName = product.querySelector('h3').textContent.toLowerCase();
                    if (productName.startsWith(searchQuery)) {
                        product.style.display = 'block'; // Show the product if it starts with the search query
                        hasVisibleItems = true;
                    } else {
                        product.style.display = 'none'; // Hide the product if it doesn't match
                    }
                });

                // Show or hide the category container based on visible items
                if (hasVisibleItems) {
                    category.style.display = 'block';
                } else {
                    category.style.display = 'none';
                }
            });
        }

        function sortProducts() {
            const categories = document.querySelectorAll('.category-container');

            categories.forEach(category => {
                const productsContainer = category.querySelector('.products-container');
                const products = Array.from(productsContainer.querySelectorAll('.menu-item'));

                // Sort products based on the current sorting order
                products.sort((a, b) => {
                    const nameA = a.querySelector('h3').textContent.toLowerCase();
                    const nameB = b.querySelector('h3').textContent.toLowerCase();
                    return isAscending ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });

                // Clear the container and append the sorted products
                productsContainer.innerHTML = '';
                products.forEach(product => productsContainer.appendChild(product));
            });

            // Toggle the sorting order for the next click
            isAscending = !isAscending;
        }
    </script>
</head>
<body>
    <header class="header">
        <a href="admin_control_centre.php" style="text-decoration: none; color: inherit;">
            <h1>Kaffeecito</h1>
        </a>
        <a class="headerButton" href="menu_admin.php">Administrar Menú</a>
        <a class="headerButton" href="manage_users.php">Administrar usuarios</a>
        <a class="headerButton" href="admin_promos.php">Administrar promociones</a>
        <a class="headerButton" href="vistaAdmin.php">Pedidos</a>
        <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
    </header>
    <main class="mainPage">
        <div class="action-buttons">
            <a href="anadir_producto.php">Añadir producto</a>
        </div>
        <div class="products-section">
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="Buscar productos..." oninput="filterProducts()">
                <img src="../resources/img/sort.png" id="sort-icon" alt="Ordenar" onclick="sortProducts()" style="cursor: pointer; width: 30px; height: 30px; margin-left: 10px;">
            </div>
            <?php
            include("conex.php");
            $link = Conectarse();

            // Fetch products grouped by category
            $categories = ["Alimentos", "Bebidas", "No disponible"]; // Add "No disponible" to the categories
            foreach ($categories as $category) {
                $query = "SELECT Id, Nombre, Precio, Imagen FROM ca_productos WHERE Categoria = '$category'";
                $result = mysqli_query($link, $query);
                $products = [];
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $products[] = $row;
                    }
                }

                if (!empty($products)) {
                    echo "<div class='category-container'>";
                    echo "<h2 class='category-title'>$category</h2>";
                    echo "<div class='products-container'>";
                    foreach ($products as $product) {
                        echo "<div class='menu-item'>";
                        echo "<img src='../" . $product['Imagen'] . "' alt='" . $product['Nombre'] . "'>";
                        echo "<h3>" . $product['Nombre'] . "</h3>";
                        echo "<p class='price'>\$" . number_format($product['Precio'], 2) . "</p>";

                        // Button container for edit, delete, and unavailable buttons
                        echo "<div class='button-container' style='display: inline-flex; gap: 10px;'>";

                        // Edit button
                        echo "<form action='editar_producto.php' method='GET' style='margin: 0;'>";
                        echo "<input type='hidden' name='idProducto' value='" . $product['Id'] . "'>";
                        echo "<button type='submit' style='background: none; border: none; cursor: pointer;' title='Editar'>";
                        echo "<img src='../resources/img/edit-button.png' alt='Editar' style='width: 25px; height: 25px;'>";
                        echo "</button>";
                        echo "</form>";

                        // Delete button
                        echo "<form action='delete_producto.php' method='POST' style='margin: 0;'>";
                        echo "<input type='hidden' name='idProducto' value='" . $product['Id'] . "'>";
                        echo "<button type='submit' style='background: none; border: none; cursor: pointer;' title='Eliminar' onclick='return confirmDelete();'>";
                        echo "<img src='../resources/img/bin.png' alt='Eliminar' style='width: 25px; height: 25px;'>";
                        echo "</button>";
                        echo "</form>";

                        // Unavailable or Restore button based on category
                        if ($category === "No disponible") {
                            // Restore button for "No disponible" items
                            echo "<form action='restore_producto.php' method='POST' style='margin: 0;' onsubmit='return confirmDisponible();'>";
                            echo "<input type='hidden' name='idProducto' value='" . $product['Id'] . "'>";
                            echo "<button type='submit' style='background: none; border: none; cursor: pointer;' title='Disponible'>";
                            echo "<img src='../resources/img/check.png' alt='Disponible' style='width: 25px; height: 25px;'>";
                            echo "</button>";
                            echo "</form>";
                        } else {
                            // Unavailable button for other categories
                            echo "<form action='set_unavailable.php' method='POST' style='margin: 0;' onsubmit='return confirmUnavailable();'>";
                            echo "<input type='hidden' name='idProducto' value='" . $product['Id'] . "'>";
                            echo "<button type='submit' style='background: none; border: none; cursor: pointer;' title='No disponible'>";
                            echo "<img src='../resources/img/unavailable.png' alt='No disponible' style='width: 25px; height: 25px;'>";
                            echo "</button>";
                            echo "</form>";
                        }

                        echo "</div>"; // Close button-container

                        echo "</div>"; // Close menu-item
                    }
                    echo "</div>";
                    echo "</div>";
                }
            }

            mysqli_close($link);
            ?>
        </div>
    </main>
</body>
</html>