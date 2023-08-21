<?php
require_once __DIR__ . '/../models/LugaresModel.php';

class LugaresController {
    private $model;

    public function __construct($conn) {
        $this->model = new LugaresModel($conn);
    }

    public function obtenerLugares() {
        try {
            $resultado = $this->model->obtenerLugares();
            return $resultado;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function obtenerLugaresOcupados() {
        try {
            $resultado = $this->model->obtenerLugaresOcupados();
            return $resultado;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function registrarSalida($lugar_id) {
        try {
            $resultado = $this->model->registrarSalida($lugar_id);
            return $resultado;
        } catch (Exception $e) {
            return $e;
        }
    }
}

?>
