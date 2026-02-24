<?php
include("accessControl.php");
checkAccess("0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="../resources/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/img/favicon_io/favicon-32x32.png">
    <link rel="manifest" href="../resources/img/favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaffeecito - Menú</title>
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
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            gap: 20px;
            margin-top: 0px;
            overflow-x: hidden;
        }

        .category-container {
            width: 90%; /* Reduce width to leave space on the edges */
            margin: 0 auto 40px auto; /* Center the container and add bottom margin */
            padding: 10px; /* Add padding to avoid touching the edges */
            box-sizing: border-box; /* Include padding in the width calculation */
            text-align: center;
        }

        .category-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center; /* Center the text */
            color: white; /* Make the text color white for better contrast */
            background-color: rgba(76, 43, 24, 0.8); /* Add a semi-transparent background */
            padding: 10px 20px; /* Add padding around the text */
            border-radius: 8px; /* Add rounded corners */
            display: inline-block; /* Ensure the background fits the text */
            margin-left: auto; /* Center horizontally */
            margin-right: auto; /* Center horizontally */
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjust columns dynamically */
            gap: 20px; /* Add spacing between items */
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            padding: 20px;
            border-radius: 8px;
            margin: 0 auto; /* Center the products-container */
            width: 100%; /* Ensure it doesn't exceed the parent container */
            min-width: 1000px;
            max-width: 1200px; /* Optional: Limit the maximum width */
            box-sizing: border-box; /* Include padding and border in the width calculation */
            min-height: 250px; /* Maintain a minimum height */
            justify-items: center; /* Center items horizontally */
            align-items: center; /* Center items vertically */
        }

        .menu-item {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .menu-item img {
            width: 100px; /* Set the width to 100px */
            height: 100px; /* Set the height to 100px */
            border-radius: 8px; /* Keep the rounded corners */
            object-fit: cover; /* Ensure the image fits within the dimensions */
        }

        .cart-container {
            flex: 1;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: sticky;
            top: 120px;
            width: 500px; /* Increased width to accommodate longer product names */
            max-width: 500px; /* Ensure it doesn't grow beyond this width */
            box-sizing: border-box; /* Include padding and border in the width */
            margin: 0 auto; /* Center the cart container */
        }

        .cart-container h2 {
            margin-top: 0;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 50px 1fr 150px; /* Adjust columns for image, name, and controls */
            align-items: center;
            gap: 10px; /* Add spacing between columns */
            margin-bottom: 10px;
        }

        .cart-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        .cart-item p {
            margin: 0;
            white-space: nowrap; /* Prevent text wrapping */
            overflow: hidden; /* Hide overflowing text */
            text-overflow: ellipsis; /* Add ellipsis for long text */
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px; /* Add spacing between buttons and price */
        }

        .quantity-controls button {
            background-color: grey;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .quantity-controls button:hover {
            background-color: darkgrey;
        }

        .quantity-controls p {
            margin: 0;
            font-weight: bold;
            white-space: nowrap; /* Prevent text wrapping */
            text-align: right; /* Align the price to the right */
            min-width: 60px; /* Ensure enough space for larger numbers */
        }

        .cart-total {
            font-weight: bold;
            margin-top: 10px;
        }

        .add-to-cart {
            background-color: #8B4513;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-to-cart:hover {
            background-color: #A0522D;
        }

        .search-container {
            width: 100%;
            min-width: 1200px;
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
</head>
<body>
    <header class="header">
        <h1>Kaffeecito</h1>
        <a class="headerButton" href="menu.php">Menú</a>
        |
        <a class="headerButton" href="../../VistaUsuarios/aboutus.html">Acerca de Nosotros</a>
        |
        <a class="headerButton" href="../../VistaUsuarios/promok.html">Promos de Temporada</a>
        |
        <a class="logoutButton" href="logout.php">Cerrar Sesión</a>
    </header>
    <main class="mainPage">
        <div class="products-section">
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="Buscar productos..." oninput="filterProducts()">
                <img src="../resources/img/sort.png" id="sort-icon" alt="Ordenar" onclick="sortProducts()" style="cursor: pointer; width: 30px; height: 30px; margin-left: 10px;">
            </div>
            <?php
            include("conex.php");
            $link = Conectarse();

            // Fetch products grouped by category
            $categories = ["Alimentos", "Bebidas"];
            foreach ($categories as $category) {
                if ($category === "Alimentos") {
                    echo "<div class='category-container'>";
                    echo "<h2 class='category-title'>$category</h2>";
                    echo "<div class='products-container'>";
                } else {
                    echo "<div class='category-container'>";
                    echo "<h2 class='category-title'>$category</h2>";
                    echo "<div class='products-container'>";
                }

                $query = "SELECT Id, Nombre, Precio, Imagen FROM ca_productos WHERE Categoria = '$category'";
                $result = mysqli_query($link, $query);
                $products = [];
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $products[] = $row;
                    }
                }

                if (!empty($products)) {
                    foreach ($products as $product) {
                        echo "<div class='menu-item'>";
                        echo "<img src='../" . $product['Imagen'] . "' alt='" . $product['Nombre'] . "'>";
                        echo "<h3>" . $product['Nombre'] . "</h3>";
                        echo "<p class='price'>$" . number_format($product['Precio'], 2) . "</p>";
                        echo "<button class='add-to-cart' data-id='" . $product['Id'] . "' data-name='" . $product['Nombre'] . "' data-price='" . $product['Precio'] . "' data-image='../" . $product['Imagen'] . "'>Añadir al carrito</button>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            }

            mysqli_close($link);
            ?>
        </div>
        <div class="cart-container">
            <h2>Carrito</h2>
            <div id="cart-items"></div>
            <p class="cart-total">Total: $<span id="cart-total">0.00</span></p>
            <button class="pagar-button" onclick="confirmPurchase()">Pagar</button>
        </div>
    </main>
    <script>
        const cart = <?php echo isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : '[]'; ?>;
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotalElement = document.getElementById('cart-total');

        // Render the cart immediately on page load
        updateCart();

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productPrice = parseFloat(button.getAttribute('data-price'));
                const productImage = button.getAttribute('data-image');

                // Check if the product is already in the cart
                const existingProduct = cart.find(item => item.id === productId);
                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push({ id: productId, name: productName, price: productPrice, image: productImage, quantity: 1 });
                }

                updateCart();
            });
        });

        function updateCart() {
            cartItemsContainer.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                cartItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    <p>${item.name}</p>
                    <div class="quantity-controls">
                        <button onclick="decreaseQuantity(${index})">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="increaseQuantity(${index})">+</button>
                        <p>$${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                `;
                cartItemsContainer.appendChild(cartItem);
                total += item.price * item.quantity;
            });

            cartTotalElement.textContent = total.toFixed(2);

            // Send the cart data to the server to store in the session
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cart), // Send the updated cart (even if empty)
            });
        }

        function decreaseQuantity(index) {
            const product = cart[index];
            if (product.quantity > 1) {
                product.quantity -= 1; // Decrease quantity by one
            } else {
                cart.splice(index, 1); // Remove the product if quantity is 1
            }
            updateCart();
        }

        function increaseQuantity(index) {
            cart[index].quantity += 1; // Increase quantity by one
            updateCart();
        }

        function confirmPurchase() {
            if (cart.length === 0) {
                alert("El carrito está vacío. Agrega productos antes de pagar.");
                return;
            }

            const confirmation = confirm("¿Estás seguro de que deseas realizar la compra?");
            if (confirmation) {
                // Clear the cart data from localStorage
                localStorage.removeItem('cart');

                // Clear the cart data from the session on the server
                fetch('clear_cart.php', {
                    method: 'POST',
                }).then(() => {
                    // Redirect to the payment confirmation page
                    window.location.href = '../html/pago.html';
                });
            }
        }

        function filterProducts() {
            const searchQuery = document.getElementById('search-bar').value.toLowerCase();
            const categories = document.querySelectorAll('.category-container');
            let hasVisibleCategories = false;

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
                    category.style.display = 'block'; // Show the category if it has visible items
                    hasVisibleCategories = true;
                } else {
                    category.style.display = 'none'; // Hide the category if no items are visible
                }
            });

            // Show or hide the "no results" message
            const noResultsMessage = document.getElementById('no-results');
            if (!hasVisibleCategories) {
                if (!noResultsMessage) {
                    const message = document.createElement('p');
                    message.id = 'no-results';
                    message.textContent = 'Lo sentimos, no encontramos ningún producto que coincida con tu búsqueda.';
                    message.style.textAlign = 'center';
                    message.style.color = '#888';
                    message.style.marginTop = '20px';
                    document.querySelector('.products-section').appendChild(message);
                }
            } else {
                if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            }
        }

        let isAscending = true; // Flag to track the sorting order

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
</body>
</html>