<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos
require_once '../../controllers/VehiculoController.php';

$vehiculoController = new VehiculoController($conn);

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $modelos = $vehiculoController->obtenerModelos();
    $colores = $vehiculoController->obtenerColores();
    $marcas = $vehiculoController->obtenerMarcas();
    $response = [
        "modelos" => $modelos,
        "colores" => $colores,
        "marcas" => $marcas
    ];

    header('Content-Type: application/json'); 
    echo json_encode($response);
}
?>
