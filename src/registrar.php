<?php
session_start();
if (isset($_SESSION["usuario_id"], $_SESSION["token"])) header("Location: index.php");
else
{
    require_once "functions/modificar_usuario.php";
    $modificar_usuario = new ModificarUsuario();
    if (isset($_POST["usuario"], $_POST["contrasena"]))
    {
        $resultado = $modificar_usuario->registrarUsuario($_POST["usuario"], $_POST["contrasena"]);
        if ($resultado) $anuncio = ["Registrado exitosamente", ""];
        if (!$resultado) $anuncio = ["Error al registrar", $modificar_usuario->error];
    }
    $tipo_navbar = "logout";
    $estado = ["registrar" => 1];
    require_once "functions/navbar_preload.php";
    $contenidos = [["titulo" => "Registrarse<form method=\"post\" action=\"?\">", "divs" => [[
        "titulo" => "",
        "texto" => "Usuario: <input type=\"text\" name=\"usuario\"><br>Contraseña: <input type=\"password\" name=\"contrasena\"><br><input type=\"submit\" value=\"Registrarse\"></form>"
    ]]]];
    require_once "template/default.phtml";
}
?>