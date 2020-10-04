<?php
require_once "secciones.php";
class Subforo extends Seccion
{
    public $subforo_id;
    public $subforo_nombre;
    public $subforo_seccion;
    public $subforo_eliminado;
    public $subforo_temas;
    public function establecerSubforo($id)
    {
        $this->subforo_id = NULL;
        $this->subforo_nombre = NULL;
        $this->subforo_seccion = NULL;
        $this->subforo_eliminado = NULL;
        $this->subforo_temas = NULL;
        $stmt = $this->mysqli->prepare("SELECT nombre,seccion FROM ".$this->prefijo."subforo WHERE id=? AND eliminado=0 LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $consulta = $stmt->get_result();
        if ($consulta->num_rows === 0) return $this->establecerError("Este subforo no existe o fue eliminado");
        $elemento = $consulta->fetch_row();
        $this->subforo_id = $id;
        $this->subforo_nombre = $elemento[0];
        $this->subforo_seccion = $elemento[1];
        $this->subforo_eliminado = 0;
        return true;
    }
    public function listarTemas($pagina)
    {
        $pagina = (int) $pagina;
        if ($pagina < 1) return $this->establecerError("La página no puede ser menor que 1");
        $offset = ($pagina - 1) * 20;
        $stmt = $this->mysqli->prepare("SELECT id,nombre,creador,fecha,fijo,estado,ultimo_mensaje FROM ".$this->prefijo."tema WHERE subforo=? AND estado!=2 ORDER BY ultimo_mensaje DESC LIMIT ?,20");
        $stmt->bind_param("ii", $this->subforo_id, $offset);
        $stmt->execute();
        $consulta = $stmt->get_result();
        $resultado = [];
        while ($elemento = $consulta->fetch_row())
        {
            $resultado[] = $elemento;
        }
        $this->subforo_temas = $resultado;
        return true;
    }
}
?>