<?php
session_start();

if(isset($_SESSION["autentificado"]) || $_SESSION["autentificado"] !== "SI" ){
    session_unset();
    session_destroy();
    header(header: "Location: ../../index.php");
    exit();
}
?>