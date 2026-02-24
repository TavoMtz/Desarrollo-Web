document.addEventListener('DOMContentLoaded', function() {
    const listaCarrito = document.getElementById('lista-carrito');
    const totalElement = document.getElementById('total');
    const totalAPagarElement = document.getElementById('total-a-pagar');
    const pagoSection = document.getElementById('pago');
    const formularioPagoSection = document.getElementById('formulario-pago');
    const confirmacionSection = document.getElementById('confirmacion');
    const tiempoEsperaElement = document.getElementById('tiempo-espera');
    const pagoForm = document.getElementById('pago-form');
    let total = 0;
    let carritoData = [];

    function actualizarCarritoVisual() {
        listaCarrito.innerHTML = '';
        total = 0;
        carritoData = JSON.parse(localStorage.getItem('carrito')) || [];
        carritoData.forEach((item, index) => {
            const listItem = document.createElement('li');
            listItem.textContent = `ID: ${item.id}, Nombre: ${item.nombre} - $${parseFloat(item.precio).toFixed(2)} `;
            const eliminarButton = document.createElement('button');
            eliminarButton.textContent = 'Eliminar';
            eliminarButton.classList.add('eliminar-item');
            eliminarButton.dataset.index = index;
            listItem.appendChild(eliminarButton);
            listaCarrito.appendChild(listItem);
            total += parseFloat(item.precio);
        });
        totalElement.textContent = `$ ${total.toFixed(2)}`;
        totalAPagarElement.textContent = `$ ${total.toFixed(2)}`;

        const botonesEliminar = document.querySelectorAll('.eliminar-item');
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function() {
                const indexAEliminar = parseInt(this.dataset.index);
                eliminarDelCarrito(indexAEliminar);
            });
        });
    }

    function eliminarDelCarrito(index) {
        carritoData.splice(index, 1);
        localStorage.setItem('carrito', JSON.stringify(carritoData));
        actualizarCarritoVisual();
    }

    function luhnCheck(num) {
        let sum = 0;
        let isEven = false;
        for (let n = num.length - 1; n >= 0; n--) {
            let digit = parseInt(num.charAt(n), 10);
            if (isEven) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }
            sum += digit;
            isEven = !isEven;
        }
        return (sum % 10) === 0;
    }

    actualizarCarritoVisual();

    document.getElementById('btn-pagar').addEventListener('click', function() {
        pagoSection.style.display = 'block';
        document.getElementById('carrito').style.display = 'none';
    });

    document.getElementById('btn-credito').addEventListener('click', function() {
        formularioPagoSection.style.display = 'block';
        pagoSection.style.display = 'none';
    });

    document.getElementById('btn-debito').addEventListener('click', function() {
        formularioPagoSection.style.display = 'block';
        pagoSection.style.display = 'none';
    });

    pagoForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value.trim();
        const tarjeta = document.getElementById('tarjeta').value.trim().replace(/\s/g, ''); // Eliminar espacios
        const fecha = document.getElementById('fecha').value.trim();
        const cvv = document.getElementById('cvv').value.trim();
        const carritoJSON = localStorage.getItem('carrito');

        let errores = [];

        if (!nombre) {
            errores.push('Por favor, ingresa el nombre del titular.');
        } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
            errores.push('El nombre del titular solo debe contener letras y espacios.');
        }

        if (!tarjeta) {
            errores.push('Por favor, ingresa el número de tarjeta.');
        } else if (!/^\d{13,19}$/.test(tarjeta)) {
            errores.push('El número de tarjeta debe tener entre 13 y 19 dígitos numéricos.');
        } else if (!luhnCheck(tarjeta)) {
            errores.push('El número de tarjeta no parece ser válido.');
        }

        if (!fecha) {
            errores.push('Por favor, ingresa la fecha de vencimiento.');
        } else if (!/^(0[1-9]|1[0-2])\/([2-9][0-9])$/.test(fecha)) {
            errores.push('El formato de la fecha de vencimiento debe ser MM/YY.');
        } else {
            const [mes, anioCorto] = fecha.split('/');
            const anioLargo = 20 + anioCorto;
            const ahora = new Date();
            const mesActual = ahora.getMonth() + 1;
            const anioActual = ahora.getFullYear();

            if (anioLargo < anioActual || (anioLargo === anioActual && parseInt(mes) < mesActual)) {
                errores.push('La fecha de vencimiento no es válida.');
            }
        }

        if (!cvv) {
            errores.push('Por favor, ingresa el CVV.');
        } else if (!/^\d{3,4}$/.test(cvv)) {
            errores.push('El CVV debe tener 3 o 4 dígitos numéricos.');
        }

        if (errores.length > 0) {
            alert(errores.join('\n'));
            return;
        }

        if (carritoJSON) {
            fetch('procesar_pago.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `nombre=${encodeURIComponent(nombre)}&tarjeta=${encodeURIComponent(tarjeta)}&fecha=${encodeURIComponent(fecha)}&cvv=${encodeURIComponent(cvv)}&carrito=${encodeURIComponent(carritoJSON)}&total=${encodeURIComponent(total.toFixed(2))}`,
            })
            .then(response => response.json())
            .then(data => {
                formularioPagoSection.style.display = 'none';
                confirmacionSection.style.display = 'block';
                tiempoEsperaElement.textContent = Math.floor(Math.random() * 5) + 2;

                if (data.status === 'success') {
                    const mensajeExito = document.createElement('p');
                    mensajeExito.textContent = data.message + (data.venta_id ? ` ID de la Venta: ${data.venta_id}` : '');
                    confirmacionSection.appendChild(mensajeExito);

                    setTimeout(() => {
                        localStorage.removeItem('carrito');
                        actualizarCarritoVisual();
                        confirmacionSection.innerHTML = '<h2>Tu pedido estará listo en:</h2><p><span id="tiempo-espera"></span> minutos...</p>';
                        confirmacionSection.style.display = 'none';
                        document.getElementById('carrito').style.display = 'block';
                    }, 3000);
                } else {
                    const mensajeError = document.createElement('p');
                    mensajeError.classList.add('error');
                    mensajeError.textContent = 'Error al procesar el pago: ' + data.message;
                    confirmacionSection.appendChild(mensajeError);
                    setTimeout(() => {
                        confirmacionSection.style.display = 'none';
                        formularioPagoSection.style.display = 'block';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                formularioPagoSection.style.display = 'none';
                confirmacionSection.style.display = 'block';
                confirmacionSection.innerHTML = '<h2 class="error">Error de red</h2><p class="error">Intenta nuevamente más tarde.</p>';
                setTimeout(() => {
                    confirmacionSection.style.display = 'none';
                    formularioPagoSection.style.display = 'block';
                }, 3000);
            });
        } else {
            alert('El carrito está vacío.');
        }
    });
});