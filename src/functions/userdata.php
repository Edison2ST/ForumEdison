<?php
include "database.php";
class UserData extends Database
{
    public $id_usuario;
    public $usuario;
    public $contrasena;
    public $token;
    public $fecha_token;
    public $mensajes;
    public $rango;
    public $creacion;
    public $silenciado;
    public function establecerToken($id, $token)
    {
        $stmt = $this->mysqli->prepare("SELECT token,fecha_token FROM ".$this->prefijo."usuario_login WHERE id=? AND token=? LIMIT 1");
        $stmt->bind_param("is", $id, $token);
        $stmt->execute();
        $consulta = $stmt->get_result();
        if ($consulta->num_rows !== 1) return $this->establecerError("No existe el token asignado para este usuario");
        $elemento = $consulta->fetch_row();
        $fecha_token = $elemento[1];
        if ($elemento[0] !== $token || time() >= strtotime($fecha_token)) return $this->establecerError("No existe el token asignado para este usuario");
        $stmt = $this->mysqli->prepare("SELECT id,nombre,contrasena,mensajes,rango,creacion,silenciado FROM ".$this->prefijo."usuario WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $elemento = $stmt->get_result()->fetch_row();
        $this->id_usuario = $elemento[0];
        $this->usuario = $elemento[1];
        $this->contrasena = $elemento[2];
        $this->token = $token;
        $this->fecha_token = $fecha_token;
        $this->mensajes = $elemento[3];
        $this->rango = $elemento[4];
        $this->creacion = $elemento[5];
        $this->silenciado = $elemento[6];
        return true;
    }
    public function comprobarIngreso($usuario, $contrasena)
    {
        sleep(1);
        $stmt = $this->mysqli->prepare("SELECT id,contrasena FROM ".$this->prefijo."usuario WHERE nombre=? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $consulta = $stmt->get_result();
        if ($consulta->num_rows !== 1) return $this->establecerError("Este usuario no existe");
        $elemento = $consulta->fetch_row();
        $contrasena_cons = $elemento[1];
        if ($contrasena_cons !== crypt($contrasena, $contrasena_cons)) return $this->establecerError("Combinación de usuario y contraseña incorrectos");
        $id_usuario = $elemento[0];
        $token = bin2hex(random_bytes(78));
        $fecha_token = date("Y-m-d H:i:s", time()+86400);
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."usuario_login(id,token,fecha_token) VALUES(?,?,?)");
        $stmt->bind_param("iss", $id_usuario, $token, $fecha_token);
        $stmt->execute();
        return $this->establecerToken($id_usuario, $token);
    }
    public function noLogin($usuario, $buscarporid = false)
    {
        $stmt = $this->mysqli->prepare("SELECT id,nombre,mensajes,rango,creacion,silenciado FROM ".$this->prefijo."usuario WHERE ".($buscarporid ? "id" : "nombre")."=? LIMIT 1");
        $stmt->bind_param(($buscarporid ? "i" : "s"), $usuario);
        $stmt->execute();
        $consulta = $stmt->get_result();
        if ($consulta->num_rows !== 1) return $this->establecerError("Este usuario no existe");
        $elemento = $consulta->fetch_row();
        $this->id_usuario = $elemento[0];
        $this->usuario = $elemento[1];
        $this->mensajes = $elemento[2];
        $this->rango = $elemento[3];
        $this->creacion = $elemento[4];
        $this->silenciado = $elemento[5];
        return true;
    }
    public function cerrarSesion()
    {
        if (!isset($this->id_usuario, $this->token)) return true;
        $stmt = $this->mysqli->prepare("DELETE FROM ".$this->prefijo."usuario_login WHERE id=? AND token=?");
        $stmt->bind_param("is", $this->id_usuario, $this->token);
        $stmt->execute();
        return true;
    }
}
?>