<?php
require_once "functions/checklogin_preload.php";
if ($ingresado !== true || $userdata->rango !== 2) header("Location: index.php");
else
{
    require_once "functions/navbar_preload.php";
    require_once "functions/secciones.php";
    $seccion = new Seccion();
    $seccion->establecerToken($userdata->id_usuario, $userdata->token);
    if (isset($_POST["nombre"]))
    {
        $resultado = $seccion->anadirSeccion($_POST["nombre"]);
        if ($resultado) $anuncio = ["Sección añadida exitosamente", ""];
        else $anuncio = ["Error al intentar añadir la sección", $seccion->error];
    }
    $contenidos = [["titulo" => "Añadir sección<form method=\"post\" action=\"?\">", "divs" => [[
        "titulo" => "",
        "texto" => "Nombre de la sección: <input type=\"text\" name=\"nombre\"><br><input type=\"submit\" value=\"Añadir sección\"></form>"
    ]]]];
    require_once "template/default.phtml";
}