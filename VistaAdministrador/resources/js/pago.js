// Declaraciones
document.addEventListener("DOMContentLoaded", () => {
    const $form = document.querySelector('#formPago');

    $form.addEventListener('submit', (event) => {
        event.preventDefault();

        if ($form.checkValidity()) {
            alert("Pago realizado con éxito. Gracias por su compra. ✅");

            setTimeout(() => {
                // Redirige al index
                window.location.href = "../../../index.php";
            }, 1000); // 1 segundo
        } else {
            $form.reportValidity();
        }
    });
});

