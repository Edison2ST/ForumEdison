<?php
// Se asume que ya existe session_start() para evitar el error de nivel E_NOTICE
function generate_csrftoken()
{
    if (!isset($_SESSION["csrf_token"])) $_SESSION["csrf_token"] = bin2hex(random_bytes(64));
    return $_SESSION["csrf_token"];
}
$csrf_token = false;
if (isset($_POST["csrf_token"]))
{
    if (!isset($_SESSION["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"])
    {
        unset($_SESSION["csrf_token"]);
        $anuncio = ["Error del token CSRF", "Intente nuevamente"];
    }
    else $csrf_token = true;
}
?>