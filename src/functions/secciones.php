<?php
require_once "database.php";
class Seccion extends Database
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
}
?>