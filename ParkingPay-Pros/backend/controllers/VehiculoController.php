<?php
require_once __DIR__ . '/../models/VehiculoModel.php';


class VehiculoController {
    private $model;

    public function __construct($conn) {
        $this->model = new VehiculoModel($conn);
    }

    public function ingresarVehiculo($marca, $modelo, $color, $matricula) {
        return $this->model->ingresarVehiculo($marca, $modelo, $color, $matricula);
    }
    public function obtenerModelos() {
        return $this->model->obtenerModelos();
    }

    public function obtenerColores() {
        return $this->model->obtenerColores();
    }

    public function obtenerMarcas() {
        return $this->model->obtenerMarcas();
    }
    public function verificarMatricula($matricula) {
        return $this->model->verificarMatriculaExistente($matricula);
    }
}

?>
