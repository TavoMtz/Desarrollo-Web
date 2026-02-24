<?php
// Archivo: guardarOrden.php
require_once 'conect.php'; // Incluir el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroOrden = $_POST['numero_orden'];

    try {
        $link = Conectarse();

        // Preparar la consulta SQL
        $stmt = $link->prepare("INSERT INTO ca_pedidos (no_orden) VALUES (?)");
        $stmt->bind_param("s", $numeroOrden);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Orden guardada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la orden']);
        }

        $stmt->close();
        mysqli_close($link);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
