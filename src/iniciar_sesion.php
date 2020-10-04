<?php
session_start();
if (isset($_SESSION["usuario_id"], $_SESSION["token"])) header("Location: index.php");
else
{
    require_once "functions/usuario.php";
    require_once "functions/csrf_token.php";
    $userdata = new Usuario();
    if (isset($_POST["usuario"], $_POST["contrasena"], $_POST["csrf_token"]) && $csrf_token)
    {
        $resultado = $userdata->comprobarIngreso($_POST["usuario"], $_POST["contrasena"]);
        if ($resultado)
        {
            $_SESSION["usuario_id"] = $userdata->id_usuario;
            $_SESSION["token"] = $userdata->token;
            header("Location: index.php");
        }
        if (!$resultado) $anuncio = ["Error al iniciar sesión", $userdata->error];
    }
    $tipo_navbar = "logout";
    $estado = ["iniciar_sesion" => 1];
    require_once "functions/navbar_preload.php";
    $contenidos = [["titulo" => "Iniciar sesión<form method=\"post\" action=\"?\">", "divs" => [[
        "titulo" => "",
        "texto" => "Usuario: <input type=\"text\" name=\"usuario\"><br>Contraseña: <input type=\"password\" name=\"contrasena\"><br><input type=\"hidden\" name=\"csrf_token\" value=\"".htmlspecialchars(generate_csrftoken())."\"><input type=\"submit\" value=\"Iniciar sesión\"></form>"
    ]]]];
    require_once "template/default.phtml";
}