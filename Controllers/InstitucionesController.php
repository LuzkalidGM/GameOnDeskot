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
}
?>