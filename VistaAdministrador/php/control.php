<?php
//vemos si el usuario y contraseña es váildo
include("conex.php");
$link = Conectarse();
$id = $_POST["ID"];
$contra = $_POST["pass"];


$querySha = "SELECT SHA2('$contra', 256) AS contraEn";
$contraSha = mysqli_query($link, $querySha);

$contraShaRow = mysqli_fetch_assoc($contraSha);
$contraSha = $contraShaRow["contraEn"];


$result = mysqli_query($link,"Select * from TC_EMPLEADOS where ID ='$id';");
$row=mysqli_fetch_array($result);

if ($id==$row["ID"] && $contraSha==$row["Contrasenia"] && $row["Estado_cuenta"]=="Activo")
{ 

if($row["Puesto"]=="Vendedor"){
    header ("Location: indexVendedores.php");
}else if($row["Puesto"]=="Administrador"){
    header ("Location: indexAdministrador.php");
}else {
    header ("Location: indexAlmacen.php");
}
exit;
}
else {
//si no existe le mando otra vez a la portada
header("Location: inicioSesion.php?errorusuario=1");
}
?>
