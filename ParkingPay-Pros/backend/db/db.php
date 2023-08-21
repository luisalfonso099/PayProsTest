<?php
$servername = "127.0.0.2"; // Cambia esto si tu servidor es diferente
$username = "root";
$password = "";
$dbname = "parking_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>