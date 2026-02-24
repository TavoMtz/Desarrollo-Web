document.addEventListener('DOMContentLoaded', () => {
    loadMenu();
});

function loadMenu() {
    fetch('conect.php?action=getMenu')
        .then(response => response.json())
        .then(data => {
            renderMenu(data);
        })
        .catch(error => {
            console.error('Error al cargar el menú:', error);
            // Mostrar un mensaje de error al usuario si es necesario
        });
}

const navButtons = document.querySelectorAll('.nav-button');
const menuSections = document.querySelectorAll('.menu-section');

navButtons.forEach(button => {
    button.addEventListener('click', () => {
        navButtons.forEach(btn => btn.classList.remove('active'));
        menuSections.forEach(section => section.classList.remove('active'));

        button.classList.add('active');
        const sectionId = button.getAttribute('data-section');
        document.getElementById(sectionId).classList.add('active');

        const subcategoryNav = document.querySelector(`#${sectionId} .subcategory-navigation`);
        if (subcategoryNav) {
            const firstSubcategoryButton = subcategoryNav.querySelector('.subcategory-button');
            if (firstSubcategoryButton) {
                firstSubcategoryButton.click();
            }
        }
    });
});

function renderMenu(data) {
    const promocionesSection = document.getElementById('promociones').querySelector('.product-grid');
    const bebidasSection = document.getElementById('bebidas');
    const alimentosSection = document.getElementById('alimentos');

    promocionesSection.innerHTML = data.promociones.map(promo => `
        <li class="product-item">
            <img src="img/promociones/${promo.imagen || 'default.jpg'}" alt="${promo.nombre}">
            <h3>${promo.nombre}</h3>
            <p class="price">$${promo.precio}</p>
            <button class="add-to-cart" data-product='${JSON.stringify({ id: promo.id, nombre: promo.nombre, precio: promo.precio })}'>Añadir al carrito</button>
        </li>
    `).join('');

    const bebidasSubcategories = ['calientes', 'frios', 'frappes'];
    const bebidasContents = {};
    bebidasSubcategories.forEach(sub => bebidasContents[sub] = bebidasSection.querySelector(`#${sub} .product-grid`));

    bebidasSubcategories.forEach(subcategory => {
        let productosFiltrados = [];
        if (data.bebidas && data.bebidas[subcategory]) {
            if (subcategory === 'calientes') {
                productosFiltrados = data.bebidas[subcategory].filter(bebida =>
                    bebida.nombre.includes('Espresso Grande') || bebida.nombre.includes('Americano Grande') || bebida.nombre.includes('Cappuccino Grande')
                ).map(bebida => ({ ...bebida, nombre: bebida.nombre.replace(' Grande', '') }));
            }
            if (productosFiltrados.length > 0) {
                bebidasContents[subcategory].innerHTML = productosFiltrados.map(bebida => `
                    <li class="product-item">
                        <img src="img/bebidas/${bebida.imagen || 'default.jpg'}" alt="${bebida.nombre}">
                        <h3>${bebida.nombre}</h3>
                        ${bebida.descripcion ? `<p class="description">${bebida.descripcion}</p>` : ''}
                        <p class="price">$${bebida.precio}</p>
                        <button class="add-to-cart" data-product='${JSON.stringify({ id: bebida.id, nombre: bebida.nombre, precio: bebida.precio })}'>Añadir al carrito</button>
                    </li>
                `).join('');
            } else if (bebidasContents[subcategory]) {
                bebidasContents[subcategory].innerHTML = '<li class="empty-subcategory">No hay productos en esta categoría.';
            }
        } else if (bebidasContents[subcategory]) {
            bebidasContents[subcategory].innerHTML = '<li class="empty-subcategory">No hay productos en esta categoría.';
        }
    });

    const alimentosSubcategories = ['desayunos', 'comidas'];
    const alimentosContents = {};
    alimentosSubcategories.forEach(sub => alimentosContents[sub] = alimentosSection.querySelector(`#${sub} .product-grid`));

    alimentosSubcategories.forEach(subcategory => {
        if (data.alimentos && data.alimentos[subcategory] && data.alimentos[subcategory].length > 0) {
            alimentosContents[subcategory].innerHTML = data.alimentos[subcategory].map(alimento => `
                <li class="product-item">
                    <img src="img/alimentos/${alimento.imagen || 'default.jpg'}" alt="${alimento.nombre}">
                    <h3>${alimento.nombre}</h3>
                    ${alimento.descripcion ? `<p class="description">${alimento.descripcion}</p>` : ''}
                    <p class="price">$${alimento.precio}</p>
                    <button class="add-to-cart" data-product='${JSON.stringify({ id: alimento.id, nombre: alimento.nombre, precio: alimento.precio })}'>Añadir al carrito</button>
                </li>
            `).join('');
        } else if (alimentosContents[subcategory]) {
            alimentosContents[subcategory].innerHTML = '<li class="empty-subcategory">No hay productos en esta categoría.';
        }
    });

    const dynamicAddToCartButtons = document.querySelectorAll('.add-to-cart');
    dynamicAddToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productData = JSON.parse(button.getAttribute('data-product'));
            let carrito = localStorage.getItem('carrito');
            carrito = carrito ? JSON.parse(carrito) : [];
            carrito.push({ id: productData.id, nombre: productData.nombre, precio: productData.precio });
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarContadorCarrito();
            alert(`"${productData.nombre}" añadido al carrito.`);
        });
    });

    function actualizarContadorCarrito() {
        const carritoCountElement = document.getElementById('carrito-count');
        let carrito = localStorage.getItem('carrito');
        carrito = carrito ? JSON.parse(carrito) : [];
        carritoCountElement.textContent = `(${carrito.length})`;
    }

    actualizarContadorCarrito();

    const initializeSubcategories = (sectionId) => {
        const subcategoryNav = document.querySelector(`#${sectionId} .subcategory-navigation`);
        if (subcategoryNav) {
            const firstButton = subcategoryNav.querySelector('.subcategory-button');
            if (firstButton) {
                firstButton.classList.add('active');
                const subcategoryId = firstButton.getAttribute('data-subcategory');
                document.querySelectorAll(`#${sectionId} .subcategory-content`).forEach(content => content.classList.remove('active'));
                document.getElementById(subcategoryId).classList.add('active');
            }

            const subcategoryButtons = subcategoryNav.querySelectorAll('.subcategory-button');
            subcategoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const subcategoryId = button.getAttribute('data-subcategory');
                    const section = button.closest('.menu-section');

                    section.querySelectorAll('.subcategory-button').forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    section.querySelectorAll('.subcategory-content').forEach(content => content.classList.remove('active'));
                    document.getElementById(subcategoryId).classList.add('active');
                });
            });
        }
    };

    initializeSubcategories('bebidas');
    initializeSubcategories('alimentos');
}