<?php
header('Content-Type: application/json');
include 'conect.php';
$link = Conectarse();

$menuData = array(
    'promociones' => array(),
    'bebidas' => array(
        'calientes' => array(),
        'frios' => array(),
        'frappes' => array()
    ),
    'alimentos' => array(
        'desayunos' => array(),
        'comidas' => array()
    )
);

// Consulta para promociones
$resultPromociones = mysqli_query($link, "SELECT id, nombre, precio, imagen FROM promociones");
while ($row = mysqli_fetch_assoc($resultPromociones)) {
    $menuData['promociones'][] = $row;
}

// Consulta para bebidas calientes
$resultBebidasCalientes = mysqli_query($link, "SELECT id, nombre, descripcion, precio, imagen FROM bebidas WHERE categoria = 'caliente'");
while ($row = mysqli_fetch_assoc($resultBebidasCalientes)) {
    $menuData['bebidas']['calientes'][] = $row;
}

// Consulta para bebidas frías
$resultBebidasFrias = mysqli_query($link, "SELECT id, nombre, descripcion, precio, imagen FROM bebidas WHERE categoria = 'frio'");
while ($row = mysqli_fetch_assoc($resultBebidasFrias)) {
    $menuData['bebidas']['frios'][] = $row;
}

// Consulta para frappés
$resultFrappes = mysqli_query($link, "SELECT id, nombre, descripcion, precio, imagen FROM bebidas WHERE categoria = 'frappe'");
while ($row = mysqli_fetch_assoc($resultFrappes)) {
    $menuData['bebidas']['frappes'][] = $row;
}

// Consulta para desayunos
$resultDesayunos = mysqli_query($link, "SELECT id, nombre, descripcion, precio, imagen FROM alimentos WHERE categoria = 'desayuno'");
while ($row = mysqli_fetch_assoc($resultDesayunos)) {
    $menuData['alimentos']['desayunos'][] = $row;
}

// Consulta para comidas
$resultComidas = mysqli_query($link, "SELECT id, nombre, descripcion, precio, imagen FROM alimentos WHERE categoria = 'comida'");
while ($row = mysqli_fetch_assoc($resultComidas)) {
    $menuData['alimentos']['comidas'][] = $row;
}

mysqli_close($link);
echo json_encode($menuData);
?>