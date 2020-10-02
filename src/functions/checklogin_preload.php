<?php
require_once "usuario.php";
session_start();
$userdata = new Usuario();
if (isset($_SESSION["usuario_id"], $_SESSION["token"]))
{
    $resultado = $userdata->establecerToken($_SESSION["usuario_id"], $_SESSION["token"]);
    if ($resultado === true)
    {
        $ingresado = true;
        $tipo_navbar = $userdata->rango === 0 ? "rango0" : "rango1";
    }
    else
    {
        unset($_SESSION["usuario_id"]);
        unset($_SESSION["token"]);
        $ingresado = false;
        $tipo_navbar = "logout";
    }
}
else
{
    $ingresado = false;
    $tipo_navbar = "logout";
}
?>