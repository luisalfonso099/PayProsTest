<?php
require_once __DIR__ . '/../models/RegistrosModel.php';

class RegistrosController {
    private $model;
    private $conn2;

    public function __construct($conn) {
        $this->model = new RegistrosModel($conn);
        $this->conn2 = $conn;
    }

    public function registrarMarca($marca) {
        try {
            $resultado = $this->model->registrarMarca($marca);
            return $resultado;
        } catch (Exception $e) {
            return false;
        }
    }
    public function registrarColor($color) {
        try {
            $resultado = $this->model->registrarColor($color);
            return $resultado;
        } catch (Exception $e) {
            return false;
        }
    }
    public function registrarModelo($modelo) {
        try {
            $resultado = $this->model->registrarModelo($modelo);
            return $resultado;
        } catch (Exception $e) {
            return false;
        }
    }

    public function existeRegistro($tipo, $nombre) {
        $table = ''; 

        switch ($tipo) {
            case 'marca':
                $table = 'Marcas';
                break;
            case 'color':
                $table = 'Colores';
                break;
            case 'modelo':
                $table = 'Modelos';
                break;
            default:
                return false; 
        }
        $count = 0;
        $sql = "SELECT COUNT(*) FROM $table WHERE nombre = ?";
        $stmt = $this->conn2->prepare($sql);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
}

?>
