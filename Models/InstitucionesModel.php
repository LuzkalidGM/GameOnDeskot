<?php
require_once __DIR__ . '/../Config/database.php';

class InstitucionesModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerInstituciones() {
        $instituciones = [];
        $sql = "SELECT * FROM instituciones_deportivas WHERE estado = 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $instituciones[] = $row;
            }
        }
        return $instituciones;
    }

    public function obtenerInstitucionPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM instituciones_deportivas WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0 ? $res->fetch_assoc() : null;
    }

    public function obtenerAreasPorInstitucion($institucion_id) {
        $stmt = $this->conn->prepare("SELECT ad.*, d.nombre AS deporte_nombre FROM areas_deportivas ad
            JOIN deportes d ON ad.deporte_id = d.id
            WHERE ad.institucion_deportiva_id = ?");
        $stmt->bind_param('i', $institucion_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $areas = [];
        while ($row = $res->fetch_assoc()) {
            $areas[] = $row;
        }
        return $areas;
    }
    public function contarInstalacionesPorInstitucion($institucion_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM instalaciones_deportivas WHERE institucion_deportiva_id = ?");
        $stmt->bind_param('i', $institucion_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row ? $row['total'] : 0;
    }

    public function contarAreasPorInstitucion($institucion_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM areas_deportivas WHERE institucion_deportiva_id = ?");
        $stmt->bind_param('i', $institucion_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row ? $row['total'] : 0;
    }


}
?>