<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos
require_once '../../controllers/VehiculoController.php';
$vehiculoController = new VehiculoController($conn);

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["matricula"])) {

    $matricula = $_GET["matricula"];
    $vehiculo = $vehiculoController->verificarMatricula($matricula);

    if ($vehiculo) {
        $response = $vehiculo;
    } else {
        $response = ["success" => false];
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
