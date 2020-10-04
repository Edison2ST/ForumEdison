<?php
require_once "functions/checklogin_preload.php";
require_once "functions/navbar_preload.php";
$contenidos = [];
if (!isset($_GET["id"])) $anuncio = ["Error al consultar el subforo", "No se asignó una id para establecer el subforo"];
else
{
    require_once "functions/subforo.php";
    $subforo = new Subforo();
    if (!$subforo->establecerSubforo($_GET["id"]) || !$subforo->listarTemas($_GET["pagina"] ?? 1)) $anuncio = ["Error al consultar el subforo", $subforo->error];
    else
    {
        $esAdministrador = $ingresado === true && $userdata->rango === 2;
        $divs = [];
        foreach ($subforo->subforo_temas as $tema)
        {
            $divs[] = ["titulo" => "<a href=\"tema.php?id=".urlencode($tema[0])."\">".htmlspecialchars($tema[1])."</a>".($esAdministrador ? " <a href=\"editar_tema.php?id=".urlencode($tema[0])."\">Editar</a>" : ""), "texto" => ""];
        }
        $contenidos[] = ["titulo" => htmlspecialchars($subforo->subforo_nombre).($esAdministrador ? " <a href=\"editar_subforo.php?id=".urlencode($subforo->subforo_id)."\">Editar</a>" : ""), "divs" => $divs];
        $contenidos[] = ["titulo" => "<a href=\"anadir_tema.php?subforo=".urlencode($subforo->subforo_id)."\">Añadir tema</a>", "divs" => []];
        $contenidos[] = ["titulo" => "Cambiar página<form method=\"get\" action=\"subforo.php\">", "divs" => [["titulo" => "", "texto" => "<input type=\"hidden\" name=\"id\" value=\"".htmlspecialchars($_GET["id"])."\">Página: <input type=\"text\" name=\"pagina\" value=\"".htmlspecialchars($_GET["pagina"] ?? 1)."\"> <input type=\"submit\" value=\"Cambiar página\"></form>"]]];
    }
}
require_once "template/default.phtml";
?>