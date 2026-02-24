<?php
include("conex.php");
$link = Conectarse();

if (isset($_GET['id'])) {
    $idPromocion = mysqli_real_escape_string($link, $_GET['id']);
    $query = "SELECT * FROM ca_promociones WHERE id = '$idPromocion'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(null);
    }
    mysqli_close($link);
}
?>