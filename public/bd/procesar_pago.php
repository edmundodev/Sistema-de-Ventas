<?php
require_once '../conexion.php'; // Tu conexión a la BD
require_once '../vendor/autoload.php'; // Si usas JWT o composer
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Obtener datos POST
$plan_id = $_POST['plan_id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
$fecha_expiracion = $_POST['fecha_expiracion'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$user_id = $_SESSION['user_id'];

// Validación básica
if (!$plan_id || !$numero_tarjeta || !$fecha_expiracion || !$cvv) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Validar formato (simulado)
if (strlen($numero_tarjeta) < 16 || strlen($cvv) != 3) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos de tarjeta inválidos']);
    exit;
}

// Simulación de pago exitoso
$pago_exitoso = true; // Simulación

if ($pago_exitoso) {
    // Fechas de suscripción
    $fecha_inicio = date('Y-m-d');
    $fecha_fin = date('Y-m-d', strtotime('+30 days'));

    // Verificar si ya tiene una suscripción activa
    $stmt = $conn->prepare("SELECT * FROM suscripciones WHERE user_id = ? AND estado = 'activa'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Ya tiene una suscripción activa
        echo json_encode(['error' => 'Ya tienes una suscripción activa.']);
        exit;
    }

    // Registrar suscripción
    $stmt = $conn->prepare("INSERT INTO suscripciones (user_id, plan_id, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, 'activa')");
    $stmt->bind_param("iiss", $user_id, $plan_id, $fecha_inicio, $fecha_fin);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Suscripción activada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al registrar suscripción']);
    }
} else {
    http_response_code(402);
    echo json_encode(['error' => 'Error en el pago']);
}
