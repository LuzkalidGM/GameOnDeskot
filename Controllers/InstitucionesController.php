<?php
require_once __DIR__ . '/../Models/InstitucionesModel.php';

class InstitucionesController {
    private $model;

    public function __construct() {
        $this->model = new InstitucionesModel();
    }

    public function listar() {
        return $this->model->obtenerInstituciones();
    }

    // Métodos para totales generales
    public function contarTotalInstalaciones() {
        $instituciones = $this->model->obtenerInstituciones();
        $total = 0;
        foreach ($instituciones as $inst) {
            $total += $this->model->contarInstalacionesPorInstitucion($inst['id']);
        }
        return $total;
    }

    public function contarTotalAreas() {
        $instituciones = $this->model->obtenerInstituciones();
        $total = 0;
        foreach ($instituciones as $inst) {
            $total += $this->model->contarAreasPorInstitucion($inst['id']);
        }
        return $total;
    }
}
?>