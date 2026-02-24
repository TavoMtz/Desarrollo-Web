// Variables
const $nombre = document.querySelector('#nombre');
const $contraseña = document.querySelector('#contraseña');
const $BotonIniSesion = document.querySelector('#BotonIniSesion');

//Funciones
// Función para validar y evitar envío si falta algún campo
const guardarDatos = (event) => {
    event.preventDefault(); // Evita que el formulario se envíe

    if ($nombre.value.trim() === '') {
        alert("El nombre no puede estar vacío.");
        return;
    }
    if ($contraseña.value.trim() === '') {
        alert("La contraseña no puede estar vacía.");
        return;
    }

    // Si pasa la validación, enviar el formulario manualmente
    event.target.closest("form").submit();
};

// Función para mostrar mensajes de error después de la redirección
const mostrarMenssaje = () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get("error") === "nombre_vacio") {
        alert("❌ El nombre no puede estar vacío.");
    } else if (params.get("error") === "contraseña_vacia") {
        alert("❌ La contraseña no puede estar vacía.");
    } else if (params.get("success") === "datos_validos") {
        alert("✅ Datos válidos.");
    }
};

//Eventos
document.addEventListener("DOMContentLoaded", mostrarMenssaje);
$BotonIniSesion.addEventListener('click', guardarDatos);
