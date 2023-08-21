<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos
require_once '../../controllers/VehiculoController.php';

$vehiculoController = new VehiculoController($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $color = $_POST["color"];
    $matricula = $_POST["matricula"];

    $response = $vehiculoController->ingresarVehiculo($marca, $modelo, $color, $matricula);
    header('Content-Type: application/json'); 
    echo json_encode($response); 
}

?>
