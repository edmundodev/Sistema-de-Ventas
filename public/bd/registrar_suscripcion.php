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

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "No autenticado"]);
    exit;
}

$usuario_id = $_SESSION['user_id'];
$plan_id = intval($_POST['plan_id']);
$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';

if (!$plan_id || !$nombre || !$apellidos || !$email || !$telefono || !$metodo_pago) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos"]);
    exit;
}

// Aquí podrías validar método de pago, datos tarjeta, etc (por simplicidad no lo pongo ahora)

// Fechas de la suscripción
$fecha_inicio = date('Y-m-d');
$fecha_fin = date('Y-m-d', strtotime('+1 month'));

// Cancelar suscripciones activas anteriores
$conn->query("UPDATE suscripciones SET activo = 0 WHERE usuario_id = $usuario_id");

// Insertar nueva suscripción
$stmt = $conn->prepare("INSERT INTO suscripciones (usuario_id, plan_id, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("iiss", $usuario_id, $plan_id, $fecha_inicio, $fecha_fin);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Suscripción activada correctamente"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al activar suscripción"]);
}
