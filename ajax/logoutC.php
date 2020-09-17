<?php

require '../config/connection.php';
session_start();
date_default_timezone_set("America/Lima");
$_SESSION['usu1'];
$_SESSION['name1'];
$_SESSION['state1'];
$enddate = date('Y-m-d H:i:s');
$_SESSION['workgroup1'];
$_SESSION['idSession1'];

$deleteSession = ejecutarConsulta("DELETE FROM session WHERE "
//        . "SessionId = '$_SESSION[idSession]' and "
        . "Usuario = '$_SESSION[usu]' ");

// -- eliminamos la sesión del usuario
if (isset($_SESSION['usu1'])) {
    unset($_SESSION['usu1']);
    unset($_SESSION['name1']);
    unset($_SESSION['state1']);
    unset($_SESSION['workgroup1']);
    unset($_SESSION['idSession1']);
}
if(isset($_SESSION['usu']) == false){
    session_regenerate_id();
}
session_destroy();
header('location: ../views/login.php');
exit();
?>
