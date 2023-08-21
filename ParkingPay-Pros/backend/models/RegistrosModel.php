<?php
class RegistrosModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrarMarca($marca) {
        $sql = "INSERT INTO marcas (nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $marca);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Marca registrada con exito"];
        } else {
            return ["success" => false, "message" => "No se pudo registrar la marca"];
        }
    }
    public function registrarModelo($modelo) {
        $sql = "INSERT INTO modelos (nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $modelo);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Modelo registrado con exito"];
        } else {
            return ["success" => false, "message" => "No se pudo registrar el modelo"];
        }
    }

    public function registrarColor($color) {
        $sql = "INSERT INTO colores (nombre) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $color);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Color registrado con exito"];
        } else {
            return ["success" => false, "message" => "No se pudo registrar el color"];
        }
    }
}
?>
