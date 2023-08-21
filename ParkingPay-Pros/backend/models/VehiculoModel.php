<?php
class VehiculoModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function ingresarVehiculo($marca, $modelo, $color, $matricula) {

        $sql = "INSERT INTO vehiculos (marca_id, modelo_id, color_id, matricula) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $marca, $modelo, $color, $matricula);
        $stmt->execute();
        $vehiculo_id = $stmt->insert_id;
        $lugar = $this->obtenerLugarDisponible();

        if ($lugar === null) {
            return ["success" => false, "message" => 'Lo sentimos no hay lugares disponibles'];
        }

        if (!$this->actualizarEstadoLugar($lugar['id'], 'ocupado')) {
            return ["success" => false, "message" => 'Erro al actualizar el status del lugar'];
        }

        // Luego, registra la entrada en la tabla 'entradas'
        $sql_entrada = "INSERT INTO entradas (vehiculo_id, lugar_id, fecha_hora_entrada) VALUES (?, ?, NOW())";
        $stmt_entrada = $this->conn->prepare($sql_entrada);
        $stmt_entrada->bind_param("ii", $vehiculo_id, $lugar['id']);
        if ($stmt_entrada->execute()) {
            return ["success" => true, "message" => 'Veiculo ingresado con exito su lugar asignado es piso '. $lugar['piso']. ' en el lugar '. $lugar['numero']];
        } else {
            return ["success" => false, "message" => 'Erro al registrar la entrada'];
        }
    }   
    private function actualizarEstadoLugar($lugar_id, $estado) {
        $sql = "UPDATE lugares SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $estado, $lugar_id);

        return $stmt->execute();
    }

    private function obtenerLugarDisponible() {
        $sql = "SELECT * FROM lugares WHERE status = 'libre' LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }

        return null; 
    }
    
    public function obtenerModelos() {
        $modelos = [];
        
        $sql = "SELECT * FROM modelos";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $modelos[] = $row;
            }
        }
        
        return $modelos;
    }
    
    public function obtenerColores() {
        $colores = [];
        
        $sql = "SELECT * FROM colores";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $colores[] = $row;
            }
        }
        
        return $colores;
    }
    
    public function obtenerMarcas() {
        $marcas = [];
        
        $sql = "SELECT * FROM marcas";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $marcas[] = $row;
            }
        }
        
        return $marcas;
    }

    public function verificarMatriculaExistente($matricula) {
        try {
            $sql = "SELECT * FROM vehiculos WHERE matricula = ?";
            $stmt = $this->conn->prepare($sql);
    
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conn->error);
            }
    
            $stmt->bind_param("s", $matricula);
            $stmt->execute();
    
            if ($stmt->error) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            $result = $stmt->get_result();
    
            if (!$result) {
                throw new Exception("Error al obtener resultados: " . $this->conn->error);
            }
    
            $vehiculo = $result->fetch_assoc();
    
            if ($vehiculo) {
                $existeEnEntradas = $this->verificarMatriculaEnEntradas($matricula);
                $vehiculo['existe_en_entradas'] = $existeEnEntradas;
                return $vehiculo; 
            } else {
                return null; 
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    
    public function verificarMatriculaEnEntradas($matricula) {
        $sql = "SELECT COUNT(*) AS count FROM entradas e
                JOIN vehiculos v ON e.vehiculo_id = v.id
                WHERE v.matricula = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['count'] > 0;
    }
    
}
?>
