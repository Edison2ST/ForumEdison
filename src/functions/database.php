<?php
class Database
{
    protected $mysqli;
    protected $prefijo;
    public $error = "";
    public function __construct()
    {
        require "database_preload.php";
        $this->mysqli = new mysqli($host, $usuario_db, $contrasena_db);
        $this->mysqli->set_charset('utf8mb4');
        $this->mysqli->select_db($basededatos);
        $this->prefijo = $prefijo;
    }
    protected function establecerError($error)
    {
        // Por lo general, los errores dependen de algún modo de la conexión a la base de datos
        // Esta función no es para errores de PHP, sino para errores dentro de ForumEdison, ej: cuando un usuario no existe
        $this->error = $error;
        return false;
    }
}
?>