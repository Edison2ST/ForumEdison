<?php
require_once "functions/checklogin_preload.php";
if ($ingresado !== true || $userdata->rango !== 2 || !isset($_GET["id"])) header("Location: index.php");
elseif (isset($_GET["id"]))
{
    require_once "functions/navbar_preload.php";
    require_once "functions/secciones.php";
    $seccion = new Seccion();
    $seccion->establecerToken($userdata->id_usuario, $userdata->token);
    $resultado = $seccion->establecerSeccion($_GET["id"]);
    if ($resultado === false)
    {
        $anuncio = ["Error al intentar establecer la sección", ""];
        $contenidos = [];
    }
    else
    {
        if (isset($_POST["nombre"]))
        {
            $resultado = $seccion->editarSeccion($_POST["nombre"]);
            if ($resultado) $anuncio = ["Sección editada exitosamente", ""];
            else $anuncio = ["Error al intentar editar la sección", $seccion->error];
        }
        $contenidos = [["titulo" => "Editar sección<form method=\"post\" action=\"?id=".urlencode($_GET["id"])."\">", "divs" => [[
            "titulo" => "",
            "texto" => "Nombre de la sección: <input type=\"text\" name=\"nombre\"><br><input type=\"submit\" value=\"Editar sección\"></form>"
        ]]]];
    }
    require_once "template/default.phtml";
}
?>