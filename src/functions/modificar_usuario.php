<?php
include "database.php";
class ModificarUsuario extends Database
{
    private function comprobarUsuario($nombre)
    {
        $longitud = strlen($nombre);
        if ($longitud >= 30 || $longitud <= 2) return $this->establecerError("El usuario debe tener entre 3 y 30 caracteres");
        $nombre = $this->usuarioLegible($nombre);
        $stmt = $this->mysqli->prepare("SELECT id FROM ".$this->prefijo."usuario WHERE nombre=? LIMIT 1");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        if ($stmt->get_result()->num_rows !== 0) return $this->establecerError("El usuario ya existe");
        $letras_mayusculas = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        $letras_minusculas = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
        for ($i = 0; $i < $longitud; $i++)
        {
            $letra = substr($nombre, $i, 1);
            if (!in_array($letra, $letras_mayusculas) && !in_array($letra, $letras_minusculas)) return $this->establecerError("El usuario solo puede contener letras");
        }
        return true;
    }
    private function usuarioLegible($nombre)
    {
        return strtoupper(substr($nombre, 0, 1)).strtolower(substr($nombre, 1));
    }
    private function encriptarContrasena($contrasena)
    {
        return crypt($contrasena, "$2y$11$".str_replace("+", ".", base64_encode(random_bytes(16))));
    }
    public function registrarUsuario($nombre, $contrasena)
    {
        if (!$this->comprobarUsuario($nombre)) return false;
        $contrasena_encriptada = $this->encriptarContrasena($contrasena);
        $nombre_legible = $this->usuarioLegible($nombre);
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."usuario(nombre,contrasena,mensajes,rango,creacion,silenciado) VALUES(?,?,0,0,'".date("Y-m-d")."','0000-00-00 00:00:00')");
        $stmt->bind_param("ss", $nombre_legible, $contrasena_encriptada);
        $stmt->execute();
        return true;
    }
}
?>