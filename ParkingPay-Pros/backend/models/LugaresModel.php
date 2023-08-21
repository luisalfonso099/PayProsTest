<?php
class LugaresModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerLugares() {
        $lugares = [];

        $sql = "SELECT l.id AS lugar_id, l.piso, l.numero, l.status, e.fecha_hora_entrada, v.matricula
                FROM lugares l
                LEFT JOIN entradas e ON l.id = e.lugar_id
                LEFT JOIN vehiculos v ON e.vehiculo_id = v.id 
                ORDER BY l.id";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lugares[] = $row;
            }
        }

        return $lugares;
    }
    public function obtenerLugaresOcupados() {
        $lugaresOcupados = [];

        $sql = "SELECT l.id AS lugar_id, l.piso, l.numero, v.matricula
                FROM lugares l
                INNER JOIN entradas e ON l.id = e.lugar_id
                INNER JOIN vehiculos v ON e.vehiculo_id = v.id";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lugaresOcupados[] = $row;
            }
        }

        return $lugaresOcupados;
    }
    public function registrarSalida($lugar_id) {
        try {
            $this->conn->begin_transaction();

            // Obtener datos del lugar ocupado
            $sql = "SELECT v.id AS vehiculo_id, e.id AS entrada_id
                    FROM entradas e
                    INNER JOIN vehiculos v ON e.vehiculo_id = v.id
                    WHERE e.lugar_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $lugar_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows === 0) {
                return ["success" => false, "message" => "Lugar no encontrado o ya liberado."];
            }

            $row = $result->fetch_assoc();
            $vehiculo_id = $row["vehiculo_id"];
            $entrada_id = $row["entrada_id"];

            // Registrar salida en la tabla "salidas"
            $sql = "INSERT INTO salidas (vehiculo_id, lugar_id, fecha_salida) VALUES (?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $vehiculo_id, $lugar_id);
            $stmt->execute();

            if ($stmt->affected_rows !== 1) {
                return ["success" => false, "message" => "Error al registrar la salida."];
            }

            // Eliminar la entrada de la tabla "entradas"
            $sql = "DELETE FROM entradas WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $entrada_id);
            $stmt->execute();

            if ($stmt->affected_rows !== 1) {
                return ["success" => false, "message" => "Error al eliminar la entrada."];
            }

            // Liberar el lugar en la tabla "lugares"
            $sql = "UPDATE lugares SET status = 'libre' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $lugar_id);
            $stmt->execute();

            if ($stmt->affected_rows !== 1) {
                return ["success" => false, "message" => "Error al liberar el lugar."];
            }

            $this->conn->commit();

            return ["success" => true, "message" => "Salida registrada con exito"];
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

}
?>
