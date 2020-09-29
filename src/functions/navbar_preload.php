<?php
if ($tipo_navbar === "logout") $navbar = [["descripcion" => "Foros", "estado" => $estado["foro"] ?? 0, "link" => "foros.php"], ["descripcion" => "Iniciar sesión", "estado" => $estado["iniciar_sesion"] ?? 0, "link" => "iniciar_sesion.php"], ["descripcion" => "Registrarse", "estado" => $estado["registrar"] ?? 0, "link" => "registrar.php"]];
?>