<?php
require_once "functions/checklogin_preload.php";
$estado = ["foro" => 1];
require_once "functions/navbar_preload.php";
require_once "functions/secciones.php";
$secciones = new Seccion();
$secciones = $secciones->listarTodo();
$contenidos = [];
$esAdministrador = $ingresado === true && $userdata->rango === 2;
foreach ($secciones as $seccion)
{
    $divs = [];
    foreach ($seccion[3] as $subforo)
    {
        $divs[] = ["titulo" => "<a href=\"subforo.php?id=".urlencode($subforo[0])."\">".htmlspecialchars($subforo[1])."</a>".($esAdministrador ? " <a href=\"editar_subforo.php?id=".urlencode($subforo[0])."\">Editar</a>" : ""), "texto" => ""];
    }
    if ($esAdministrador) $divs[] = ["titulo" => "<a href=\"anadir_subforo.php?seccion=".urlencode($seccion[0])."\">Añadir subforo</a>", "texto" => ""];
    $contenidos[] = ["titulo" => htmlspecialchars($seccion[1]).($esAdministrador ? " <a href=\"editar_seccion.php?id=".urlencode($seccion[0])."\">Editar</a>" : ""), "divs" => $divs];
}
if ($esAdministrador) $contenidos[] = ["titulo" => "<a href=\"anadir_seccion.php\">Añadir sección</a>", "divs" => []];
require_once "template/default.phtml";