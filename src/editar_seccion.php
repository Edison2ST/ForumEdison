<?php
require_once "functions/checklogin_preload.php";
if ($ingresado !== true || $userdata->rango !== 2 || !isset($_GET["id"])) header("Location: index.php");
elseif (isset($_GET["id"]))
{
    require_once "functions/navbar_preload.php";
    require_once "functions/secciones.php";
    require_once "functions/csrf_token.php";
    $seccion = new Seccion();
    $seccion->establecerToken($userdata->id_usuario, $userdata->token);
    $resultado = $seccion->establecerSeccion($_GET["id"]);
    if ($resultado === false)
    {
        $anuncio = ["Error al intentar establecer la sección", $seccion->error];
        $contenidos = [];
    }
    else
    {
        if (isset($_POST["nombre"], $_POST["csrf_token"]) && $csrf_token)
        {
            $resultado = $seccion->editarSeccion($_POST["nombre"]);
            if ($resultado) $anuncio = ["Sección editada exitosamente", ""];
            else $anuncio = ["Error al intentar editar la sección", $seccion->error];
        }
        elseif (isset($_POST["eliminar_nombre"], $_POST["csrf_token"]) && $csrf_token)
        {
            $resultado = $seccion->eliminarSeccion($_POST["eliminar_nombre"]);
            if ($resultado) $anuncio = ["Sección eliminada exitosamente", ""];
            else $anuncio = ["Error al intentar eliminar la sección", $seccion->error];
        }
        $contenidos = [["titulo" => "Editar sección<form method=\"post\" action=\"?id=".urlencode($_GET["id"])."\">", "divs" => [[
            "titulo" => "",
            "texto" => "Nombre de la sección: <input type=\"text\" name=\"nombre\" value=\"".htmlspecialchars($seccion->seccion_nombre)."\"><br><input type=\"hidden\" name=\"csrf_token\" value=\"".htmlspecialchars(generate_csrftoken())."\"><input type=\"submit\" value=\"Editar sección\"></form><form method=\"post\" action=\"?id=".urlencode($_GET["id"])."\">"
        ],[
            "titulo" => "Eliminar la sección",
            "texto" => "Para confirmar que desea eliminar esta sección, introduzca el nombre actual de esta sección: <input type=\"text\" name=\"eliminar_nombre\"><input type=\"hidden\" name=\"csrf_token\" value=\"".htmlspecialchars(generate_csrftoken())."\"><input type=\"submit\" value=\"Eliminar sección\"></form>"
        ]
        ]]];
    }
    require_once "template/default.phtml";
}
?>