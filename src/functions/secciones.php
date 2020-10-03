<?php
require_once "usuario.php";
class Seccion extends Usuario
{
    public $id;
    public $nombre;
    public $eliminado;
    public $subforos;
    public function listarTodasLasSecciones()
    {
        $consulta = $this->mysqli->query("SELECT id FROM ".$this->prefijo."seccion WHERE eliminado=0 ORDER BY id ASC");
        $resultado = [];
        while ($seccion = $consulta->fetch_row())
        {
            $resultado[] = $seccion[0];
        }
        return $resultado;
    }
    public function establecerSeccion($id)
    {
        $stmt = $this->mysqli->prepare("SELECT nombre FROM ".$this->prefijo."seccion WHERE id=? AND eliminado=0 LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $consulta = $stmt->get_result();
        if ($consulta->num_rows === 0) return $this->establecerError("Esta sección no existe o fue eliminada");
        $elemento = $consulta->fetch_row();
        $stmt = $this->mysqli->prepare("SELECT id,nombre FROM ".$this->prefijo."subforo WHERE seccion=? AND eliminado=0");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $consulta = $stmt->get_result();
        $subforos = [];
        while ($elemento2 = $consulta->fetch_row())
        {
            $subforos[] = [$elemento2[0], $elemento2[1], 0];
        }
        $this->id = $id;
        $this->nombre = $elemento[0];
        $this->eliminado = 0;
        $this->subforos = $subforos;
        return true;
    }
    public function listarTodo()
    {
        $secciones = $this->listarTodasLasSecciones();
        $resultado = [];
        foreach ($secciones as $seccion)
        {
            $this->establecerSeccion($seccion);
            $resultado[] = [$this->id, $this->nombre, $this->eliminado, $this->subforos];
        }
        return $resultado;
    }
    public function anadirSeccion($nombre)
    {
        if ($this->rango !== 2) return $this->establecerError("El usuario no posee los permisos suficientes para realizar esta acción");
        if (strlen($nombre) < 3 || strlen($nombre) > 30) return $this->establecerError("El nombre de la sección debe contener entre 3 y 30 caracteres");
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."seccion(nombre,eliminado) VALUES(?,0)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $id_seccion = $stmt->insert_id;
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."seccion_registro(id,id_mod,nombre,eliminado,fecha,usuario) VALUES(?,1,?,0,'".date("Y-m-d H:i:s")."',?)");
        $stmt->bind_param("iss", $id_seccion, $nombre, $this->id_usuario);
        $stmt->execute();
        return true;
    }
    public function editarSeccion($nombre)
    {
        if ($this->rango !== 2) return $this->establecerError("El usuario no posee los permisos suficientes para realizar esta acción");
        if (strlen($nombre) < 3 || strlen($nombre) > 30) return $this->establecerError("El nombre de la sección debe contener entre 3 y 30 caracteres");
        $stmt = $this->mysqli->prepare("UPDATE ".$this->prefijo."seccion SET nombre=? WHERE id=?");
        $stmt->bind_param("si", $nombre, $this->id);
        $stmt->execute();
        $stmt = $this->mysqli->prepare("SELECT id_mod FROM ".$this->prefijo."seccion_registro WHERE id=? ORDER BY id_mod DESC LIMIT 1");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $consulta = $stmt->get_result();
        $next_idmod = $consulta->num_rows === 0 ? 1 : $consulta->fetch_row()[0] + 1;
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."seccion_registro(id,id_mod,nombre,eliminado,fecha,usuario) VALUES(?,?,?,0,'".date("Y-m-d H:i:s")."',?)");
        $stmt->bind_param("iisi", $this->id, $next_idmod, $nombre, $this->id_usuario);
        $stmt->execute();
        return true;
    }
    public function eliminarSeccion($nombre)
    {
        if ($this->rango !== 2) return $this->establecerError("El usuario no posee los permisos suficientes para realizar esta acción");
        if ($nombre !== $this->nombre) return $this->establecerError("El nombre otorgado no coincide con el nombre de la sección (se distinguen mayúsculas y minúsculas)");
        $stmt = $this->mysqli->prepare("UPDATE ".$this->prefijo."seccion SET eliminado=1 WHERE id=?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt = $this->mysqli->prepare("SELECT id_mod FROM ".$this->prefijo."seccion_registro WHERE id=? ORDER BY id_mod DESC LIMIT 1");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $consulta = $stmt->get_result();
        $next_idmod = $consulta->num_rows === 0 ? 1 : $consulta->fetch_row()[0] + 1;
        $stmt = $this->mysqli->prepare("INSERT INTO ".$this->prefijo."seccion_registro(id,id_mod,nombre,eliminado,fecha,usuario) VALUES(?,?,?,1,'".date("Y-m-d H:i:s")."',?)");
        $stmt->bind_param("iisi", $this->id, $next_idmod, $nombre, $this->id_usuario);
        $stmt->execute();
        return true;
    }
}
?>