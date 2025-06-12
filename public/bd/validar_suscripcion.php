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
session_start();
$usuario_id = $_SESSION['user_id'] ?? null;

if (!$usuario_id) {
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

$sql = "SELECT * FROM suscripciones WHERE usuario_id = ? AND estado = 'activa' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $suscripcion = $res->fetch_assoc();
    echo json_encode([
        "estado" => "activa",
        "plan" => $suscripcion['plan'],
        "fecha_fin" => $suscripcion['fecha_fin']
    ]);
} else {
    echo json_encode(["estado" => "inactiva"]);
}
