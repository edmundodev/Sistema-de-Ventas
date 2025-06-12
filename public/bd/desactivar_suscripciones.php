<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida"]);
    exit;
}
function desactivarSuscripcionesVencidas($conn) {
    $hoy = date('Y-m-d');
    $sql = "UPDATE suscripciones SET estado = 'inactiva' WHERE fecha_fin < ? AND estado = 'activa'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hoy);
    $stmt->execute();
}

// Ejecutar
desactivarSuscripcionesVencidas($conn);

// Puedes devolver un JSON si quieres usarlo desde fetch
echo json_encode(["status" => "ok"]);
