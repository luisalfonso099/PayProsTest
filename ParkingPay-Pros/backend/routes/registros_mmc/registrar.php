<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos

require_once '../../controllers/RegistrosController.php';

$registrosController = new RegistrosController($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo_registro = $_POST["tipo_registro"]; 
    $nombre = $_POST["nombre"];

    if ($registrosController->existeRegistro($tipo_registro, $nombre)) {
        $response = ["success" => false, "message" => "Ya existe un registro con el mismo nombre"];
    } else {
        switch ($tipo_registro) {
            case "marca":
                $resultado = $registrosController->registrarMarca($nombre);
                break;
            case "color":
                $resultado = $registrosController->registrarColor($nombre);
                break;
            case "modelo":
                $resultado = $registrosController->registrarModelo($nombre);
                break;
            default:
                $resultado = false;
                break;
        }
        $response = $resultado;
    }
    header('Content-Type: application/json'); 
    echo json_encode($response);
}

?>
