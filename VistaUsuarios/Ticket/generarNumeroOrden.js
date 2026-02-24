// Variables 
const containerOrden = document.getElementById('containerOrden'); // Añade esto

// Funciones 
let generarNumeroOrden = () => {
    let fecha = new Date();
    let numeroOrden = (fecha.getTime() + Math.floor(Math.random() * 1000)).toString().slice(-6);
    return numeroOrden;
}

let guardarOrdenEnBD = async (numeroOrden) => {
    try {
        const response = await fetch('guardarOrden.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({numero_orden: numeroOrden}),
        });
        
        const data = await response.json();
        if (!data.success) {
            console.error('Error al guardar:', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

let renderNumeroOrden = (numeroOrden) => {
    const $numeroDeOrden = /*html*/`
        <div class="mostrar">
            <h2>¡Gracias por tu compra!</h2>
            <h3>Tu número de orden es: ${numeroOrden}</h3>
            <h4>Se ha enviado el número de orden a tu correo</h4>
            <h4>Recuerda tenerlo listo a la hora de recoger tu pedido</h4>
            <a class="btnRegresarIndex" href="../../index.php">Volver a Home</a>
        </div>
    `;
    containerOrden.innerHTML = $numeroDeOrden; // Usa la variable declarada
    guardarOrdenEnBD(numeroOrden);
}

// Eventos
document.addEventListener('DOMContentLoaded', () => {
    renderNumeroOrden(generarNumeroOrden());
}); 