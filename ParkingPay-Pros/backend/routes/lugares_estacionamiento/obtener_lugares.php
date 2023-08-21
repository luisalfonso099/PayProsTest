<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos
require_once '../../controllers/LugaresController.php';
$lugaresController = new LugaresController($conn);

if ($_SERVER["REQUEST_METHOD"] === "GET" ) {

    $lugares = $lugaresController->obtenerLugares();

    if ($lugares) {
        $response = $lugares;
    } else {
        $response = ["success" => false];
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
