<?php
session_start();
if (isset($_SESSION["usuario_id"], $_SESSION["token"])) header("Location: index.php");
else
{
    include "functions/userdata.php";
    $userdata = new UserData();
    if (isset($_POST["usuario"], $_POST["contrasena"]))
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
    include "functions/navbar_preload.php";
    $contenidos = [["titulo" => "Iniciar sesión<form method=\"post\" action=\"?\">", "divs" => [[
        "titulo" => "",
        "texto" => "Usuario: <input type=\"text\" name=\"usuario\"><br>Contraseña: <input type=\"password\" name=\"contrasena\"><br><input type=\"submit\" value=\"Iniciar sesión\"></form>"
    ]]]];
    include "template/default.phtml";
}