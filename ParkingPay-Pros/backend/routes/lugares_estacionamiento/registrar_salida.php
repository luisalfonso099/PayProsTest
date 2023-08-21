<?php
require_once '../../db/db.php'; // Incluye el archivo de conexión a la base de datos
require_once '../../controllers/LugaresController.php';

$lugaresController = new LugaresController($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lugar_id = $_POST["lugar_id"];
    $response = $lugaresController->registrarSalida($lugar_id);
    header('Content-Type: application/json'); 
    echo json_encode($response); 
}
?>
