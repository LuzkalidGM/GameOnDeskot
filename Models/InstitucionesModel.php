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
}
?>