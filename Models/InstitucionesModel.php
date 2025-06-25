<?php
require_once __DIR__ . '/../Config/database.php';

class InstitucionesModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Retorna todas las instituciones activas
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
}
?>