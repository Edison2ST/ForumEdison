<?php
if ($tipo_navbar === "logout") $navbar = [["descripcion" => "Foros", "estado" => $estado["foro"] ?? 0, "link" => "index.php"], ["descripcion" => "Iniciar sesión", "estado" => $estado["iniciar_sesion"] ?? 0, "link" => "iniciar_sesion.php"], ["descripcion" => "Registrarse", "estado" => $estado["registrar"] ?? 0, "link" => "registrar.php"]];
if ($tipo_navbar === "rango0") $navbar = [["descripcion" => "Foros", "estado" => $estado["foro"] ?? 0, "link" => "index.php"], ["descripcion" => "Cerrar sesión", "estado" => $estado["cerrar_sesion"] ?? 0, "link" => "cerrar_sesion.php"]];
if ($tipo_navbar === "rango1") $navbar = [["descripcion" => "Foros", "estado" => $estado["foro"] ?? 0, "link" => "index.php"], ["descripcion" => "Rangos", "estado" => $estado["rangos"] ?? 0, "link" => "rangos.php"], ["descripcion" => "Cerrar sesión", "estado" => $estado["cerrar_sesion"] ?? 0, "link" => "cerrar_sesion.php"]];
?>