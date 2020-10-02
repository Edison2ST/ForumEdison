<?php
require_once "functions/usuario.php";
session_start();
if (!isset($_SESSION["usuario_id"], $_SESSION["token"])) header("Location: index.php");
else
{
    $userdata = new Usuario();
    $userdata->establecerToken($_SESSION["usuario_id"], $_SESSION["token"]);
    $userdata->cerrarSesion();
    unset($_SESSION["usuario_id"]);
    unset($_SESSION["token"]);
    header("Location: index.php");
}
?>